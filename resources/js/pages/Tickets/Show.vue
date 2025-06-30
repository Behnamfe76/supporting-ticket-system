<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { capitalize, generateStatusStyles } from '@/lib/utils';
import MessagesTimeline from '@/partials/Tickets/MessagesTimeline.vue';
import ReplyForm from '@/partials/Tickets/ReplyForm.vue';
import { type BreadcrumbItem, ReplyType } from '@/types';
import { ArrowUturnLeftIcon } from '@heroicons/vue/20/solid';
import { Head } from '@inertiajs/vue3';
import { useEcho } from '@laravel/echo-vue';
import moment from 'moment';
import Dialog from 'primevue/dialog';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';

const replyModal = ref(false);

const props = defineProps(['ticket', 'auth']);
const userId = props.auth.user.id;

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
    items: [],
});

useEcho(`tickets.reply.user.${userId}`, '.ticket.reply.created', (e: any) => {
    // console.log('New reply received:', e);
    prepareMessages(e.reply, 'shift');
});

const prepareMessages = (item: any, method: string = 'push') => {
    const isCurrentUser = props.auth.user.name === item.author.name;
    const isUserRole = isCurrentUser && props.auth.roles && props.auth.roles.includes('user');

    const data = {
        id: item.id,
        author: isCurrentUser ? 'You' : capitalize(item.author.name),
        isCurrentUser,
        attachment: item.attachment ?? Boolean(item?.media[0]?.length),
        body: `<p>${item.content}</p>`,
        date: capitalize(moment(item.created_at).toNow() + ' at ' + moment(item.created_at).format('HH:mm:ss')),
        datetime: item.created_at,
        seen_at: item.seen_at,
        color: isCurrentUser ? '#4F46E5' : '#64748B',
        icon: isCurrentUser
            ? isUserRole
                ? 'UserRound'
                : 'UserRoundCheck'
            : item.author.roles && item.author.roles.includes('user')
              ? 'UserRound'
              : 'UserRoundCheck',
    };

    if (method === 'shift') {
        message.items.unshift(data);
    } else {
        message.items.push(data);
    }
};

onBeforeMount(() => {
    const ticket = props.ticket.data;
    const replies = props.ticket.data.replies;

    message.subject = ticket.subject;
    message.sender = ticket.submitter.email;
    message.status = ticket.status;
    if (replies.length) {
        replies.forEach((item: any) => {
            prepareMessages(item);
        });
    }
});

function onObserve(item: any) {
    axios.post(route('notifications.mark-as-read'), { reply_id: item.id });
}
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
                                                            @click="replyModal = true"
                                                            type="button"
                                                            class="relative inline-flex items-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:z-10 hover:bg-gray-50 focus:z-10"
                                                        >
                                                            <ArrowUturnLeftIcon class="-ml-0.5 size-5 text-gray-400" aria-hidden="true" />
                                                            Reply
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Message header -->
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
                                                >{{ capitalize(message.status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Message Timeline -->
                                <messages-timeline :items="message.items" @onObserve="onObserve" />
                            </div>
                        </section>
                    </main>

                    <!-- reply modal -->
                    <Dialog
                        v-model:visible="replyModal"
                        maximizable
                        modal
                        header="Reply Ticket"
                        :style="{ width: '50rem' }"
                        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
                    >
                        <reply-form :ticket="props.ticket.data.slug" submissionRoute="tickets.reply" />
                    </Dialog>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
