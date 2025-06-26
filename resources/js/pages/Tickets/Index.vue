<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Tabs } from '@/components/ui/tabs';
import { SimpleTable } from '@/components/ui/tables/simple-table';
import { Head } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import SimpleTableSkeleton from '@/components/ui/tables/simple-table/SimpleTableSkeleton.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';

const loading = ref(false);
const tabs = reactive([
    { name: 'All', href: '#', count: 52, current: true },
    { name: 'Open', href: '#', count: 6, current: false },
    { name: 'In Progress', href: '#', count: 4, current: false },
    { name: 'Closed', href: '#', current: false }
]);

const tableData = reactive({
    thead: ['Name', 'Title', 'Department', 'Status', 'Role'],
    tbody: [
        {
            cells: [
                'Lindsay Walton',
                'Front-end Developer',
                'Optimization',
                { value: 'Active', badge: true, badgeType: 'success' },
                'Member',
            ],
            operations: [
                { label: 'Edit', action: 'edit', icon: 'Pen' },
                { label: 'Delete', action: 'delete', icon: 'Trash2' }
            ]
        },
        {
            cells: [
                'Courtney Henry',
                'Designer',
                'UX',
                { value: 'Inactive', badge: true, badgeType: 'danger' },
                'Admin',
            ],
            operations: [
                { label: 'Edit', action: 'edit', icon: 'Pen' }
            ]
        }
    ]
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/tickets'
    }
];

function handleOperationClick({ action, rowIdx }: { action: string, rowIdx: number }) {
    alert(`Operation: ${action} on row ${rowIdx + 1}`);
}
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
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
