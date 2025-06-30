<script setup lang="ts">
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

import CreateEmployeeForm from '@/partials/Admin/CreateEmployeeForm.vue';
import { watch } from 'vue';
import { useToast } from 'vue-toastification';

// eslint-disable-next-line vue/valid-define-props
const props = defineProps(['errors', 'employee']);

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
            toast.error(props.errors[item]);
        }
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <create-employee-form
            :employee="{
                name: employee.name,
                email: employee.email,
                role: employee.roles[0].name,
                slug: employee.slug,
            }"
            buttonTitle="Update Employee"
            submission-route="admin.employees.update"
            mode="edit"
        />
    </AdminLayout>
</template>
