<script setup lang="ts">
import { Button } from '@/components/ui/button';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { SimpleTable } from '@/components/ui/tables/simple-table';
import SimpleTableSkeleton from '@/components/ui/tables/simple-table/SimpleTableSkeleton.vue';
import { Tabs } from '@/components/ui/tabs';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Pen } from 'lucide-vue-next';
import { reactive, ref } from 'vue';

const loading = ref(false);
const tabs = reactive([
    { name: 'All', href: '#', count: 52, current: true },
    { name: 'Open', href: '#', count: 6, current: false },
    { name: 'In Progress', href: '#', count: 4, current: false },
    { name: 'Closed', href: '#', current: false },
]);

const tableData = reactive({
    thead: ['Name', 'Title', 'Department', 'Status', 'Role'],
    tbody: [
        {
            cells: [
                'Lindsay Walton',
                'Front-end Developer',
                'Optimization',
                {
                    value: 'Active',
                    badge: true,
                    badgeType: 'success',
                },
                'Member',
            ],
            operations: [
                { label: 'Edit', action: 'edit', icon: 'Pen' },
                { label: 'Delete', action: 'delete', icon: 'Trash2' },
            ],
        },
        {
            cells: [
                'Courtney Henry',
                'Designer',
                'UX',
                {
                    value: 'Inactive',
                    badge: true,
                    badgeType: 'danger',
                },
                'Admin',
            ],
            operations: [{ label: 'Edit', action: 'edit', icon: 'Pen' }],
        },
    ],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/tickets',
    },
];

function handleOperationClick({ action, rowIdx }: { action: string; rowIdx: number }) {
    alert(`Operation: ${action} on row ${rowIdx + 1}`);
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Header -->
            <Button @click="router.get(route('tickets.create'))" class="group flex cursor-pointer items-center overflow-hidden px-4 py-2 transition-all duration-300">
                <span class="transition-all duration-300">Create A Ticket</span>
                <Pen
                    class="-translate-x-2 opacity-0 transition-all duration-300 group-hover:ml-2 group-hover:translate-x-0 group-hover:opacity-100"
                />
            </Button>

            <!-- Tabs -->
            <Tabs :tabs="tabs" />

            <!-- Main Content -->
            <SimpleTable v-if="!loading" :data="tableData" @operation-click="handleOperationClick" />
            <SimpleTableSkeleton v-else :rows="10" />

            <!-- Pagination -->
            <Pagination />
        </div>
    </AppLayout>
</template>
