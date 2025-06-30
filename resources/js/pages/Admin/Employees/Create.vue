<script setup lang="ts">

import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import { watch } from 'vue';
import { useToast } from 'vue-toastification';
import EmployeeForm from '@/partials/Admin/EmployeeForm.vue';

// eslint-disable-next-line vue/valid-define-props
const props = defineProps(["errors"]);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employees',
        href: '/admin/employees',
    },
    {
        title: 'Create',
        href: '/admin/employees/create',
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
        <employee-form buttonTitle="Create Employee" submission-route="admin.employees.store" mode="create" />
    </AdminLayout>
</template>
