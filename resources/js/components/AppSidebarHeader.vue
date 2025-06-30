<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import {Bell} from "lucide-vue-next";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { router } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="flex justify-between h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>
        <div>
            <!-- Profile dropdown -->
            <Menu as="div" class="relative ml-3">
                <div>
                    <MenuButton class="cursor-pointer relative">
                        <Bell />
                        <span
                            v-if="$page.props.auth.notifications.length"
                            class="absolute -top-3 -right-2 z-10 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 min-w-[1.25rem] flex items-center justify-center border border-white dark:border-neutral-800 shadow"
                        >
                            {{ $page.props.auth.notifications.length }}
                        </span>
                    </MenuButton>
                </div>
                <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems class="absolute h-[15rem] overflow-y-auto right-0 z-10 mt-2 w-[20rem] origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden">
                        <MenuItem v-for="notif in $page.props.auth.notifications">
                            <span @click="router.get(route('tickets.show', {ticket: notif.data.ticket_slug}))" :class="['px-4 hover:bg-gray-200 py-2 text-sm cursor-pointer text-gray-700 flex flex-col']">
                                <span class="font-bold">New reply on ticket :</span>
                                <span>{{notif.data.ticket_subject}}</span>
                            </span>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </header>
</template>
