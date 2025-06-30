<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import UserForm from '@/partials/Admin/UserForm.vue';
import { watch } from 'vue';
import { useToast } from 'vue-toastification';

// eslint-disable-next-line vue/valid-define-props
const props = defineProps(['errors', 'user']);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employees',
        href: '/admin/employees',
    },
    {
        title: 'Edit',
        href: '/admin/employees/create',
    },
];
const toast = useToast();
watch(props, () => {
    if (props.errors) {
        for (const item in props.errors) {
            toast.error(props.errors[item]);
        }
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <user-form
            :user="{
                name: user.name,
                email: user.email,
                slug: user.slug,
            }"
            buttonTitle="Update User"
            submission-route="admin.users.update"
            mode="edit"
        />
    </AdminLayout>
</template>
