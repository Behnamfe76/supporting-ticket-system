<script setup lang="ts">

import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import { watch } from 'vue';
import { useToast } from 'vue-toastification';
import UserForm from '@/partials/Admin/UserForm.vue';

// eslint-disable-next-line vue/valid-define-props
const props = defineProps(["errors"]);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/admin/users',
    },
    {
        title: 'Create',
        href: '/admin/users/create',
    },
];
const toast = useToast();
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

    <AdminLayout :breadcrumbs="breadcrumbs">
        <user-form buttonTitle="Create User" submission-route="admin.users.store" mode="create" />
    </AdminLayout>
</template>
