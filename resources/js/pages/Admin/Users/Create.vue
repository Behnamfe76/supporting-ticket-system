<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { SingleSelect } from '@/components/ui/selects/single-select';
import { Textarea } from '@/components/ui/textarea';
import { DropZone } from '@/components/ui/uploads/drop-zone';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
import { FormValidator } from '@/lib/form-validator';
import { LoaderCircle } from 'lucide-vue-next';
import { watch } from 'vue';
import { useToast } from 'vue-toastification';

// eslint-disable-next-line vue/valid-define-props
const props = defineProps(["errors"]);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/tickets',
    },
    {
        title: 'Create',
        href: '/tickets/create',
    },
];
const toast = useToast();
const form: InertiaForm<{
    subject: string;
    content: string;
    priority: string;
    file_data: null | { path: string, file_name: string };
}> = useForm({
    subject: '',
    content: '',
    priority: 'medium',
    file_data: null,
});
const createTicket = () => {
    const validated = FormValidator(form, {
        subject: ["required"],
        content: ["required"],
    })

    if (validated) {
        form.post(route('tickets.store'), {
            onSuccess: (res) => {
                console.log(res)
            }
        })
    }
};

const priorities = [
    { id: 'high', name: 'High', value: 'high' },
    { id: 'medium', name: 'Medium', value: 'medium' },
    { id: 'low', name: 'Low', value: 'low' },
];

const uploadMeta = {
    url: route('temporary-upload'),
    multiple: false,
    maxSize: 5 * 1024 * 1024, // 5MB
    allowedTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'],
};

const handleUploaded = (fileData: { path: string, file_name: string }) => {

    form.file_data = fileData;
};

watch(props, () => {
    if (props.errors) {
        for (const item in props.errors) {
            toast.error(props.errors[item])
        }
    }
})

</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <form @submit.prevent="createTicket" class="space-y-6">
                <!-- subject/priority -->
                <div class="flex gap-4 items-baseline">
                    <!-- subject -->
                    <div class="grid w-full gap-2">
                        <Label for="subject">Subject</Label>
                        <Input id="subject" v-model="form.subject" type="text" class="mt-1 block w-full"
                            placeholder="write a subject for ticket ..." />
                        <InputError :message="form.errors.subject" />
                    </div>

                    <!-- priority -->
                    <div class="grid w-full gap-2">
                        <Label for="subject">Priority</Label>
                        <SingleSelect v-model="form.priority" :options="priorities" placeholder="Select priority" />
                        <InputError :message="form.errors.priority" />
                    </div>
                </div>

                <!-- content -->
                <div class="grid gap-2">
                    <Label for="content">Content</Label>
                    <Textarea id="content" v-model="form.content" class="mt-1 block w-full"
                        placeholder="write the content for ticket ..." :rows="4" />
                    <InputError :message="form.errors.content" />
                </div>

                <!-- dropzone -->
                <div class="grid gap-2">
                    <DropZone :meta="uploadMeta" @uploaded="handleUploaded" />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing">
                        <span>Create Ticket</span>
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    </Button>

                    <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                        <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
