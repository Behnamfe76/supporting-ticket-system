<script setup lang="ts">
import type { HTMLAttributes, Reactive } from 'vue';
import { cn } from '@/lib/utils';
import { ChevronDownIcon } from '@heroicons/vue/16/solid';

interface TabsProps {
    class?: HTMLAttributes['class'],
    tabs: Reactive<({
        name: string;
        href: string;
        count: number;
        current: boolean;
    } | {
        name: string;
        href: string;
        current: boolean;
        count?: undefined;
    })[]>
}

const props = defineProps<TabsProps>();
</script>

<template>
    <div data-slot="tabs" :class="cn('', props.class)">
        <div class="grid grid-cols-1 sm:hidden">
            <select aria-label="Select a tab"
                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                <option v-for="tab in tabs" :key="tab.name" :selected="tab.current">{{ tab.name }}</option>
            </select>
            <ChevronDownIcon
                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500"
                aria-hidden="true" />
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a @click="() => {
                        tabs.map(item => item.current = tab.name === item.name)
                    }" v-for="tab in tabs" :key="tab.name" href="#" :class="[
                                    tab.current
                                        ? 'border-indigo-500 text-indigo-600'
                                        : 'border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700',
                                    'flex border-b-2 px-1 py-4 text-sm font-medium whitespace-nowrap',
                                ]" :aria-current="tab.current ? 'page' : undefined">
                        {{ tab.name }}
                        <span v-if="tab.count" :class="[
                            tab.current ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-900',
                            'ml-3 hidden rounded-full px-2.5 py-0.5 text-xs font-medium md:inline-block',
                        ]">{{ tab.count }}</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>
