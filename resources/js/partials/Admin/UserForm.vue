<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { FormValidator } from '@/lib/form-validator';
import type { InertiaForm } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import Alert from '@/components/ui/alert/Alert.vue';
import { computed } from 'vue';

type CreateUserForm = {
    name: string | null;
    email: string | null;
    password: string | null;
    password_confirmation: string | null;
};

const props = defineProps<{
    user?:
        | {
        name: string;
        email: string;
        slug: string;
    }
    submissionRoute: string;
    buttonTitle: string;
    mode: string;
}>();

const form: InertiaForm<CreateUserForm> = useForm({
    name: props.user?.name ?? null,
    email: props.user?.email ?? null,
    password: null,
    password_confirmation: null
});

const nameProxy = computed({
    get: () => form.name ?? '',
    set: v => form.name = v === '' ? null : v
});
const emailProxy = computed({
    get: () => form.email ?? '',
    set: v => form.email = v === '' ? null : v
});
const passwordProxy = computed({
    get: () => form.password ?? '',
    set: v => form.password = v === '' ? null : v
});
const passwordConfirmationProxy = computed({
    get: () => form.password_confirmation ?? '',
    set: v => form.password_confirmation = v === '' ? null : v
});

const formSubmission = () => {
    const validated = FormValidator(form, {
        name: ['required'],
        email: props.mode !== 'edit' ? ['required', 'email'] : [],
        password: [props.mode !== 'edit' ? 'required' : 'nullable', { min: 8 }, 'password'],
        password_confirmation: [props.mode !== 'edit' ? 'required' : 'nullable', { min: 8 }]
    });

    if (validated) {
        if (props.mode === 'edit') {
            form.put(route(props.submissionRoute, { user: props.user?.slug }), {});
        } else {
            form.post(route(props.submissionRoute, { user: props.user?.slug }), {});
        }
    }
};
</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <form @submit.prevent="formSubmission" class="space-y-6">
            <!-- name/email -->
            <div class="flex items-baseline gap-4">
                <!-- name -->
                <div class="grid w-full gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="nameProxy" type="text" class="mt-1 block w-full"
                           placeholder="write user's name..." />
                    <InputError :message="form.errors.name" />
                </div>
                <!-- email -->
                <div class="grid w-full gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        :disabled="mode === 'edit'"
                        id="email"
                        v-model="emailProxy"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="write user's email ..."
                    />
                    <InputError :message="form.errors.email" />
                </div>
            </div>

            <!-- password/password_confirmation -->
            <div class="flex items-baseline gap-4">
                <!-- password -->
                <div class="grid w-full gap-2">
                    <Label for="password">Password</Label>
                    <Input id="password" v-model="passwordProxy" type="password" class="mt-1 block w-full" />
                    <InputError :message="form.errors.password" />
                </div>
                <!-- password_confirmation -->
                <div class="grid w-full gap-2">
                    <Label for="password_confirmation">Password Confirmation</Label>
                    <Input id="password_confirmation" v-model="passwordConfirmationProxy" type="password"
                           class="mt-1 block w-full" />
                    <InputError :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div v-if="mode === 'edit'">
                <Alert status="info">
                    Passwords are not required, but if filled, the password would be changed.
                </Alert>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing">
                    <span>{{ buttonTitle }}</span>
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                </Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </div>
</template>
