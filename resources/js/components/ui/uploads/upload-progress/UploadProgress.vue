<script setup lang="ts">
import { computed } from 'vue';
const props = defineProps({
    progress: { type: Number, required: true },
    filename: { type: String, required: true },
    size: { type: Number, required: true },
    status: { type: String, default: 'progress' }, // 'progress', 'success', 'error'
});

const readableSize = computed(() => {
    const kb = props.size / 1024;
    if (kb < 1024) return `${kb.toFixed(1)} KB`;
    return `${(kb / 1024).toFixed(1)} MB`;
});

const progressBarColor = computed(() => {
    switch (props.status) {
        case 'success':
            return 'bg-green-600 dark:bg-green-500';
        case 'error':
            return 'bg-red-600 dark:bg-red-500';
        default:
            return 'bg-blue-600 dark:bg-blue-500';
    }
});
</script>

<template>
    <!-- File Uploading Progress Form -->
    <div>
        <!-- Uploading File Content -->
        <div class="mb-2 flex justify-between items-center">
            <div class="flex items-center gap-x-3">
                <span
                    class="size-8 flex justify-center items-center border border-gray-200 text-gray-500 rounded-lg dark:border-neutral-700 dark:text-neutral-500">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg>
                </span>
                <div>
                    <p class="text-sm font-medium text-gray-800 dark:text-white">{{ filename }}</p>
                    <p class="text-xs text-gray-500 dark:text-neutral-500">{{ readableSize }}</p>
                </div>
            </div>
            <div class="inline-flex items-center gap-x-2">
                <slot name="actions" />
            </div>
        </div>
        <!-- End Uploading File Content -->

        <!-- Progress Bar -->
        <div class="flex items-center gap-x-3 whitespace-nowrap">
            <div class="flex w-full h-2 bg-gray-200 rounded-full overflow-hidden dark:bg-neutral-700" role="progressbar"
                :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100">
                <div class="flex flex-col justify-center rounded-full overflow-hidden text-xs text-white text-center whitespace-nowrap transition duration-500"
                    :class="progressBarColor"
                    :style="`width: ${progress}%`"></div>
            </div>
            <div class="w-6 text-end">
                <span class="text-sm text-gray-800 dark:text-white">{{ progress }}%</span>
            </div>
        </div>
        <!-- End Progress Bar -->
    </div>
    <!-- End File Uploading Progress Form -->
</template>
