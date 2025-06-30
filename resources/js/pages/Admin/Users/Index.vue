<script setup lang="ts">
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { SimpleTable } from '@/components/ui/tables/simple-table';
import SimpleTableSkeleton from '@/components/ui/tables/simple-table/SimpleTableSkeleton.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { onBeforeMount, reactive, ref } from 'vue';
import moment from 'moment';

interface TableCells {
    slug: string;
    name: string;
    email: string;
    created_at: string;
}
type TableOperations = Array<{
    label: string;
    action: string;
    icon: string;
}>;

const props = defineProps(['users']);
const loading = ref(false);

const tableData: {
    thead: string[];
    tbody: {
        cells: TableCells;
        meta: { slug: string };
        operations: TableOperations;
    }[];
} = reactive({
    thead: ['Name', 'Email', 'Created Date'],
    tbody: [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/admin/users',
    },
];

function handleOperationClick({ action, rowIdx }: { action: string; rowIdx: number }) {
    if(action === 'more'){
        router.get(route('admin.tickets.show', {ticket: tableData.tbody[rowIdx].meta.slug}))
    }
}

onBeforeMount(() => {
    // initializing table data
    props.users.data.forEach((element: TableCells) => {
        const cells: any = [];
        let meta = {};
        Object.entries(element).forEach(([key, value]) => {
            if (key === 'status') {
                cells.push({
                    value: value,
                    badge: true,
                    badgeType: value === 'closed' ? 'danger' : value === 'open' ? 'success' : 'warning',
                });
            } else if (key === 'created_at') {
                cells.push(moment(value).toNow());
            } else if (key === 'slug') {
                meta = {slug: value};
            } else {
                cells.push(value);
            }
        });
        tableData.tbody.push({
            cells,
            meta,
            operations: [
                {
                    label: 'More',
                    action: 'more',
                    icon: 'Eye',
                },
            ],
        });
    });
});

</script>

<template>
    <Head title="Users" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Main Content -->
            <SimpleTable v-if="!loading" :data="tableData" @operation-click="handleOperationClick" />
            <SimpleTableSkeleton v-else :rows="10" />

            <!-- Pagination -->
            <Pagination v-if="props.users.total > 10" />
        </div>
    </AdminLayout>
</template>
