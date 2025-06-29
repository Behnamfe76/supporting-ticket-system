<script setup lang="ts">
import Pagination from '@/components/ui/pagination/Pagination.vue';
import { SimpleTable } from '@/components/ui/tables/simple-table';
import SimpleTableSkeleton from '@/components/ui/tables/simple-table/SimpleTableSkeleton.vue';
import { Tabs } from '@/components/ui/tabs';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { onBeforeMount, onMounted, reactive, ref, watch } from 'vue';
import moment from 'moment';

interface TableCells {
    slug: string;
    subject: string;
    priority: string;
    status: string;
    created_at: string;
}
type TableOperations = Array<{
    label: string;
    action: string;
    icon: string;
}>;

const props = defineProps(['tickets', 'ticketCount']);
const loading = ref(false);
const tabs = reactive([
    { name: 'All', href: '#', count: 0, code: 'total', current: true },
    { name: 'Open', href: '#', count: 0, code: 'open', current: false },
    { name: 'In Progress', href: '#', count: 0, code: 'in_progress', current: false },
    { name: 'Closed', href: '#', count: 0, code: 'closed', current: false },
]);
const tableData: {
    thead: string[];
    tbody: {
        cells: TableCells;
        meta: { slug: string };
        operations: TableOperations;
    }[];
} = reactive({
    thead: ['Subject', 'Priority', 'Status', 'Created Date'],
    tbody: [],
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/admin/tickets',
    },
];

function handleOperationClick({ action, rowIdx }: { action: string; rowIdx: number }) {
    if(action === 'more'){
        router.get(route('admin.tickets.show', {ticket: tableData.tbody[rowIdx].meta.slug}))
    }
}

onBeforeMount(() => {
    // initializing the current tab
    const params = route().params;
    if (params.status) {
        tabs.map((item) => (item.current = item.code === params.status));
    }

    // initializing ticket counts
    if (props.ticketCount) {
        props.ticketCount.forEach((element: { status: string; count: number }) => {
            tabs.forEach((tab) => {
                if (tab.code === element.status) {
                    tab.count = element.count;
                }
                if (tab.code === 'total') {
                    tab.count += element.count;
                }
            });
        });
    }

    // initializing table data
    props.tickets.data.forEach((element: TableCells) => {
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

onMounted(() => {
    watch(
        () => tabs,
        () => {
            const activeTab = tabs.find((item) => item.current);
            loading.value = true;
            router.get(
                route(
                    'admin.tickets.index',
                    {
                        status: activeTab?.code,
                    },
                    {
                        preserveState: true,
                        preserveScroll: true,
                        onFinish: () => {
                            loading.value = false;
                        },
                    },
                ),
            );
        },
        { deep: true },
    );
});

</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- Tabs -->
            <Tabs :tabs="tabs" />

            <!-- Main Content -->
            <SimpleTable v-if="!loading" :data="tableData" @operation-click="handleOperationClick" />
            <SimpleTableSkeleton v-else :rows="10" />

            <!-- Pagination -->
            <Pagination v-if="props.tickets.total > 10" />
        </div>
    </AdminLayout>
</template>
