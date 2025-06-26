<script setup lang="ts">
import Skeleton from '@/components/ui/skeleton/Skeleton.vue';
import { computed } from 'vue';

interface Props {
  columns?: number;
  rows?: number;
}

const props = withDefaults(defineProps<Props>(), {
  columns: 5,
  rows: 3,
});

const columnsArr = computed(() => Array.from({ length: props.columns }));
const rowsArr = computed(() => Array.from({ length: props.rows }));
</script>

<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-300">
      <thead>
        <tr>
          <th v-for="(_, idx) in columnsArr" :key="'sk-th-' + idx" class="py-3.5 px-3">
            <Skeleton class="h-4 w-24" />
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        <tr v-for="(_, rowIdx) in rowsArr" :key="'sk-row-' + rowIdx">
          <td v-for="(_, colIdx) in columnsArr" :key="'sk-td-' + colIdx" class="px-3 py-4">
            <Skeleton class="h-4 w-full" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
