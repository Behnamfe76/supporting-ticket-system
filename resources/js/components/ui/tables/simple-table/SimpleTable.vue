<script setup lang="ts">
import { defineEmits } from 'vue';
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import Icon from '@/components/Icon.vue';
import SimpleTableNotFound from './SimpleTableNotFound.vue';

interface TableCell {
  value: string;
  badge?: boolean;
  badgeType?: string; // e.g., 'success', 'warning', etc.
}

interface TableRow {
  cells: (string | TableCell)[];
  operations?: { label: string; action: string; icon: string }[];
}

interface TableData {
  thead: string[];
  tbody: TableRow[];
}

interface TableProps {
  class?: HTMLAttributes['class'];
  data: TableData;
}

const props = defineProps<TableProps>();
const emit = defineEmits(['operation-click']);

function onOperationClick(action: string, rowIdx: number) {
  emit('operation-click', { action, rowIdx });
}
</script>

<template>
  <div :class="cn('', props.class)">
    <div v-if="!props.data.tbody || props.data.tbody.length === 0">
      <SimpleTableNotFound />
    </div>
    <div v-else class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-300">
        <thead>
          <tr>
            <th v-for="(head, idx) in props.data.thead" :key="'th-' + idx" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900">
              {{ head }}
            </th>
            <th v-if="props.data.tbody.some(row => row.operations)" class="py-3.5 px-3 text-left text-sm font-semibold text-gray-900">Operations</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <tr v-for="(row, rowIdx) in props.data.tbody" :key="'row-' + rowIdx">
            <td v-for="(cell, cellIdx) in row.cells" :key="'cell-' + cellIdx" class="px-3 py-4 text-sm whitespace-nowrap">
              <span v-if="typeof cell === 'object' && cell.badge" :class="[
                'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                cell.badgeType === 'success' ? 'bg-green-50 text-green-700 ring-green-600/20' :
                cell.badgeType === 'warning' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' :
                cell.badgeType === 'danger' ? 'bg-red-50 text-red-700 ring-red-600/20' :
                'bg-gray-50 text-gray-700 ring-gray-600/20'
              ]">
                {{ cell.value }}
              </span>
              <span v-else>{{ typeof cell === 'object' ? cell.value : cell }}</span>
            </td>
            <td v-if="row.operations" class="px-3 py-4 text-sm whitespace-nowrap flex gap-2">
              <span v-for="(op, opIdx) in row.operations" :key="'op-' + opIdx" class="cursor-pointer inline-flex items-center justify-center p-1 rounded hover:bg-gray-100" :title="op.label" @click="onOperationClick(op.action, rowIdx)">
                <Icon :name="op.icon" class="w-5 h-5 text-indigo-600" />
              </span>
            </td>
            <td v-else-if="props.data.tbody.some(row => row.operations)" class="px-3 py-4 text-sm whitespace-nowrap"></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
