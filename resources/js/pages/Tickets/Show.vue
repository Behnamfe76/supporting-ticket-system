<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import {
    ArchiveBoxIcon as ArchiveBoxIconMini,
    ArrowUturnLeftIcon,
    ChevronDownIcon,
    ChevronUpIcon,
    EllipsisVerticalIcon,
    FolderArrowDownIcon,
    PencilIcon,
    UserPlusIcon,
} from '@heroicons/vue/20/solid';
import { Paperclip, CheckCheck, Check } from 'lucide-vue-next';

import AppLayout from '@/layouts/AppLayout.vue';
import { capitalize, generateStatusStyles, getImageName } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import moment from 'moment';
import { onBeforeMount, reactive } from 'vue';

type ReplyType = Array<{
    id: number;
    author: string;
    attachment: boolean | string;
    body: string;
    date: string;
    datetime: string;
    seen_at: null | string;
}>;

const props = defineProps(['ticket', 'auth']);
console.log(props.ticket);
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tickets',
        href: '/tickets',
    },
    {
        title: 'Show',
        href: '/tickets/show',
    },
];
const message: {
    subject: string;
    sender: string;
    status: string;
    items: ReplyType;
} = reactive({
    subject: 'Re: New pricing for existing customers',
    sender: 'joearmstrong@example.com',
    status: 'Open',
    items: [
        //   {
        //       id: 1,
        //       author: 'Joe Armstrong',
        //       date: 'Yesterday at 7:24am',
        //       datetime: '2021-01-28T19:24',
        //       body: "<p>Thanks so much! Can't wait to try it out.</p>",
        //   },
        //   {
        //       id: 2,
        //       author: 'Monica White',
        //       date: 'Wednesday at 4:35pm',
        //       datetime: '2021-01-27T16:35',
        //       body: `
        //   <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Malesuada at ultricies tincidunt elit et, enim. Habitant nunc, adipiscing non fermentum, sed est a, aliquet. Lorem in vel libero vel augue aliquet dui commodo.</p>
        //   <p>Nec malesuada sed sit ut aliquet. Cras ac pharetra, sapien purus vitae vestibulum auctor faucibus ullamcorper. Leo quam tincidunt porttitor neque, velit sed. Tortor mauris ornare ut tellus sed aliquet amet venenatis condimentum. Convallis accumsan et nunc eleifend.</p>
        //   <p><strong style="font-weight: 600;">Monica White</strong><br/>Customer Service</p>
        // `,
        //   },
        //   {
        //       id: 3,
        //       author: 'Joe Armstrong',
        //       date: 'Wednesday at 4:09pm',
        //       datetime: '2021-01-27T16:09',
        //       body: `
        //   <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Malesuada at ultricies tincidunt elit et, enim. Habitant nunc, adipiscing non fermentum, sed est a, aliquet. Lorem in vel libero vel augue aliquet dui commodo.</p>
        //   <p>Nec malesuada sed sit ut aliquet. Cras ac pharetra, sapien purus vitae vestibulum auctor faucibus ullamcorper. Leo quam tincidunt porttitor neque, velit sed. Tortor mauris ornare ut tellus sed aliquet amet venenatis condimentum. Convallis accumsan et nunc eleifend.</p>
        //   <p>– Joe</p>
        // `,
        //   },
    ],
});

onBeforeMount(() => {
    const ticket = props.ticket.data;
    const replies = props.ticket.data.replies;

    message.subject = ticket.subject;
    message.sender = ticket.submitter.email;
    message.status = ticket.status;
    if (replies.length) {
        replies.forEach((item: ReplyType, index: number) => {
            message.items.push({
                id: index,
                author: props.auth.user.name === item.author.name ? 'You' : capitalize(item.author.name),
                attachment: item.attachment,
                body: `<p>${item.content}</p>`,
                date: capitalize(moment(item.created_at).toNow() + ' at ' + moment(item.created_at).format('HH:mm:ss')),
                datetime: item.created_at,
                seen_at: item.seen_at,
            });
        });
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex h-full flex-col">
                <div class="flex min-h-0 flex-1 overflow-hidden">
                    <!-- Main area -->
                    <main class="min-w-0 flex-1 border-t border-gray-200 xl:flex">
                        <section aria-labelledby="message-heading" class="flex h-full min-w-0 flex-1 flex-col overflow-hidden xl:order-last">
                            <!-- Top section -->
                            <div class="shrink-0 border-b border-gray-200 bg-white">
                                <!-- Toolbar-->
                                <div class="flex h-16 flex-col justify-center">
                                    <div class="px-4 sm:px-6 lg:px-8">
                                        <div class="flex justify-between py-3">
                                            <!-- Left buttons -->
                                            <div>
                                                <div class="isolate inline-flex rounded-md shadow-xs sm:space-x-3 sm:shadow-none">
                                                    <span class="inline-flex sm:shadow-xs">
                                                        <button
                                                            type="button"
                                                            class="relative inline-flex items-center gap-x-1.5 rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10"
                                                        >
                                                            <ArrowUturnLeftIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Reply
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="relative -ml-px hidden items-center gap-x-1.5 bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10 sm:inline-flex"
                                                        >
                                                            <PencilIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Note
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="relative -ml-px hidden items-center gap-x-1.5 rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10 sm:inline-flex"
                                                        >
                                                            <UserPlusIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Assign
                                                        </button>
                                                    </span>

                                                    <span class="hidden space-x-3 lg:flex">
                                                        <button
                                                            type="button"
                                                            class="relative -ml-px hidden items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10 sm:inline-flex"
                                                        >
                                                            <ArchiveBoxIconMini class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Archive
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="relative -ml-px hidden items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10 sm:inline-flex"
                                                        >
                                                            <FolderArrowDownIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Move
                                                        </button>
                                                    </span>

                                                    <Menu as="div" class="relative -ml-px block sm:shadow-xs lg:hidden">
                                                        <div>
                                                            <MenuButton
                                                                class="relative inline-flex items-center gap-x-1.5 rounded-r-md bg-white px-2 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-10 sm:rounded-md sm:px-3"
                                                            >
                                                                <span class="sr-only sm:hidden">More</span>
                                                                <span class="hidden sm:inline">More</span>
                                                                <ChevronDownIcon class="size-5 text-gray-400 sm:-mr-1" aria-hidden="true" />
                                                            </MenuButton>
                                                        </div>

                                                        <transition
                                                            enter-active-class="transition ease-out duration-100"
                                                            enter-from-class="transform opacity-0 scale-95"
                                                            enter-to-class="transform opacity-100 scale-100"
                                                            leave-active-class="transition ease-in duration-75"
                                                            leave-from-class="transform opacity-100 scale-100"
                                                            leave-to-class="transform opacity-0 scale-95"
                                                        >
                                                            <MenuItems
                                                                class="absolute right-0 z-10 mt-2 w-36 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
                                                            >
                                                                <div class="py-1">
                                                                    <MenuItem v-slot="{ active }">
                                                                        <a
                                                                            href="#"
                                                                            :class="[
                                                                                active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                                'block px-4 py-2 text-sm sm:hidden',
                                                                            ]"
                                                                            >Note</a
                                                                        >
                                                                    </MenuItem>
                                                                    <MenuItem v-slot="{ active }">
                                                                        <a
                                                                            href="#"
                                                                            :class="[
                                                                                active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                                'block px-4 py-2 text-sm sm:hidden',
                                                                            ]"
                                                                            >Assign</a
                                                                        >
                                                                    </MenuItem>
                                                                    <MenuItem v-slot="{ active }">
                                                                        <a
                                                                            href="#"
                                                                            :class="[
                                                                                active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                                'block px-4 py-2 text-sm',
                                                                            ]"
                                                                            >Archive</a
                                                                        >
                                                                    </MenuItem>
                                                                    <MenuItem v-slot="{ active }">
                                                                        <a
                                                                            href="#"
                                                                            :class="[
                                                                                active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                                'block px-4 py-2 text-sm',
                                                                            ]"
                                                                            >Move</a
                                                                        >
                                                                    </MenuItem>
                                                                </div>
                                                            </MenuItems>
                                                        </transition>
                                                    </Menu>
                                                </div>
                                            </div>

                                            <!-- Right buttons -->
                                            <nav aria-label="Pagination">
                                                <span class="isolate inline-flex rounded-md shadow-xs">
                                                    <a
                                                        href="#"
                                                        class="relative inline-flex items-center rounded-l-md bg-white px-3 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10"
                                                    >
                                                        <span class="sr-only">Next</span>
                                                        <ChevronUpIcon class="size-5" aria-hidden="true" />
                                                    </a>
                                                    <a
                                                        href="#"
                                                        class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10"
                                                    >
                                                        <span class="sr-only">Previous</span>
                                                        <ChevronDownIcon class="size-5" aria-hidden="true" />
                                                    </a>
                                                </span>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <!-- Message header -->
                            </div>

                            <div class="min-h-0 flex-1 overflow-y-auto">
                                <div class="bg-white pt-5 pb-6 shadow-sm">
                                    <div class="px-4 sm:flex sm:items-baseline sm:justify-between sm:px-6 lg:px-8">
                                        <div class="sm:w-0 sm:flex-1">
                                            <h1 id="message-heading" class="text-lg font-medium text-gray-900">
                                                {{ message.subject }}
                                            </h1>
                                            <p class="mt-1 truncate text-sm text-gray-500">{{ message.sender }}</p>
                                        </div>

                                        <div class="mt-4 flex items-center justify-between sm:mt-0 sm:ml-6 sm:shrink-0 sm:justify-start">
                                            <span
                                                :class="generateStatusStyles(message.status)"
                                                class="inline-flex items-center rounded-full px-3 py-0.5 text-sm font-medium"
                                                >{{ capitalize(message.status) }}</span
                                            >
                                            <Menu as="div" class="relative ml-3 inline-block text-left">
                                                <div>
                                                    <MenuButton
                                                        class="-my-2 flex items-center rounded-full bg-white p-2 text-gray-400 hover:text-gray-600 focus:outline-hidden focus-visible:ring-2 focus-visible:ring-blue-600"
                                                    >
                                                        <span class="sr-only">Open options</span>
                                                        <EllipsisVerticalIcon class="size-5" aria-hidden="true" />
                                                    </MenuButton>
                                                </div>

                                                <transition
                                                    enter-active-class="transition ease-out duration-100"
                                                    enter-from-class="transform opacity-0 scale-95"
                                                    enter-to-class="transform opacity-100 scale-100"
                                                    leave-active-class="transition ease-in duration-75"
                                                    leave-from-class="transform opacity-100 scale-100"
                                                    leave-to-class="transform opacity-0 scale-95"
                                                >
                                                    <MenuItems
                                                        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-hidden"
                                                    >
                                                        <div class="py-1">
                                                            <MenuItem v-slot="{ active }">
                                                                <button
                                                                    type="button"
                                                                    :class="[
                                                                        active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                        'flex w-full justify-between px-4 py-2 text-sm',
                                                                    ]"
                                                                >
                                                                    <span>Copy email address</span>
                                                                </button>
                                                            </MenuItem>
                                                            <MenuItem v-slot="{ active }">
                                                                <a
                                                                    href="#"
                                                                    :class="[
                                                                        active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                        'flex justify-between px-4 py-2 text-sm',
                                                                    ]"
                                                                >
                                                                    <span>Previous conversations</span>
                                                                </a>
                                                            </MenuItem>
                                                            <MenuItem v-slot="{ active }">
                                                                <a
                                                                    href="#"
                                                                    :class="[
                                                                        active ? 'bg-gray-100 text-gray-900 outline-hidden' : 'text-gray-700',
                                                                        'flex justify-between px-4 py-2 text-sm',
                                                                    ]"
                                                                >
                                                                    <span>View original</span>
                                                                </a>
                                                            </MenuItem>
                                                        </div>
                                                    </MenuItems>
                                                </transition>
                                            </Menu>
                                        </div>
                                    </div>
                                </div>
                                <!-- Thread section-->
                                <ul role="list" class="space-y-2 py-4 sm:space-y-4 sm:px-6 lg:px-8">
                                    <li v-for="item in message.items" :key="item.id" class="bg-white px-4 py-6 shadow-sm sm:rounded-lg sm:px-6">
                                        <!-- message header -->
                                        <div class="sm:flex sm:items-baseline sm:justify-between">
                                            <h3 class="text-base font-medium">
                                                <span class="text-gray-900">{{ item.author }}</span>
                                                {{ ' ' }}
                                                <span class="text-gray-600">wrote</span>
                                            </h3>
                                            <p class="mt-1 text-sm whitespace-nowrap text-gray-600 sm:mt-0 sm:ml-3">
                                                <time :datetime="item.datetime">{{ item.date }}</time>
                                            </p>
                                        </div>

                                        <!-- message header -->
                                        <div class="mt-4 space-y-6 text-sm text-gray-800" v-html="item.body" />

                                        <!-- message footer -->
                                        <div class="mt-4 flex justify-between space-y-6 text-sm text-gray-800">
                                            <!-- message attachments -->
                                            <div class="mb-0">
                                                <a
                                                    class="hover:text-blue-500 flex gap-1 items-center"
                                                    v-if="item.attachment && typeof item.attachment === 'string'"
                                                    :href="item.attachment"
                                                >
                                                    <Paperclip />
                                                    <span>{{getImageName(item.attachment)}}</span>
                                                </a>
                                            </div>
                                            <!-- message seen data -->
                                            <div class="flex items-center gap-2">
                                                <Check v-if="!item.seen_at" />
                                                <div v-else class="flex items-center gap-2">
                                                    <span>{{ moment(item.seen_at).format('HH:mm') }}</span>
                                                    <CheckCheck />
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </main>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
