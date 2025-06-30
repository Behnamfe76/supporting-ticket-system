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

    </AppLayout>
</template>
