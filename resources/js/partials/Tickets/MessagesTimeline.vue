<script setup lang="ts">
import { Paperclip, CheckCheck, Check } from 'lucide-vue-next';
import Icon from '@/components/Icon.vue';
import Timeline from 'primevue/timeline';
import Card from 'primevue/card';
import moment from 'moment';
import { ReplyType } from '@/types';

defineProps<{
    items: ReplyType;
}>();
</script>

<template>
    <div class="card my-4">
        <Timeline :value="items" align="alternate" class="customized-timeline">
            <template #marker="slotProps">
                <span
                    class="z-10 flex h-8 w-8 items-center justify-center rounded-full text-white shadow-sm"
                    :style="{ backgroundColor: slotProps.item.color }"
                >
                    <Icon :name="slotProps.item.icon" class="h-4 w-4" />
                </span>
            </template>
            <template #content="slotProps">
                <Card :class="['mt-4', slotProps.item.isCurrentUser ? 'bg-blue-50' : 'bg-gray-50']">
                    <template #title>
                        <div class="flex items-center justify-between">
                            <span :class="slotProps.item.isCurrentUser ? 'font-semibold text-blue-700' : 'text-gray-900'">
                                {{ slotProps.item.author }}
                            </span>
                            <span class="text-sm text-gray-600">
                                {{ slotProps.item.date }}
                            </span>
                        </div>
                    </template>
                    <template #content>
                        <div class="prose max-w-none" v-html="slotProps.item.body"></div>

                        <div class="mt-4 flex items-center justify-between">
                            <!-- Attachment section -->
                            <div v-if="slotProps.item.attachment && typeof slotProps.item.attachment === 'string'" class="flex items-center gap-2">
                                <a :href="slotProps.item.attachment" class="flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                    <Paperclip class="h-4 w-4" />
                                    <span>Attachment</span>
                                </a>
                            </div>

                            <!-- Seen status -->
                            <div v-if="slotProps.item.isCurrentUser" class="flex items-center gap-2 text-gray-600">
                                <template v-if="!slotProps.item.seen_at">
                                    <Check class="h-4 w-4" />
                                    <span>Sent</span>
                                </template>
                                <template v-else>
                                    <span>{{ moment(slotProps.item.seen_at).format('HH:mm') }}</span>
                                    <CheckCheck class="h-4 w-4" />
                                    <span>Seen</span>
                                </template>
                            </div>
                        </div>
                    </template>
                </Card>
            </template>
        </Timeline>
    </div>
</template>

<style lang="scss" scoped>
@media screen and (max-width: 960px) {
    ::v-deep(.customized-timeline) {
        .p-timeline-event:nth-child(even) {
            flex-direction: row;

            .p-timeline-event-content {
                text-align: left;
            }
        }

        .p-timeline-event-opposite {
            flex: 0;
        }
    }
}
</style>
