<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { DropZone } from '@/components/ui/uploads/drop-zone';
import { useForm } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
import { FormValidator } from '@/lib/form-validator';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    ticket: string,
    submissionRoute: string
}>();

const form: InertiaForm<{
    content: string;
    file_data: null | { path: string, file_name: string };
}> = useForm({
    content: '',
    file_data: null
});
const replyTicket = () => {
    const validated = FormValidator(form, {
        content: ['required']
    });

    if (validated) {
        form.post(route(props.submissionRoute, { ticket: props.ticket }), {});
    }
};

const uploadMeta = {
    url: route('temporary-upload'),
    multiple: false,
    maxSize: 5 * 1024 * 1024, // 5MB
    allowedTypes: ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml']
};

const handleUploaded = (fileData: { path: string, file_name: string }) => {
    form.file_data = fileData;
};

</script>

<template>
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <form @submit.prevent="replyTicket" class="space-y-6">
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
                    <span>Reply Ticket</span>
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                </Button>

                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                    <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </div>
</template>
