<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref, defineEmits } from 'vue';
const emit = defineEmits();
const props = defineProps<{
    item: any;
}>();

const el = ref<HTMLElement | null>(null);
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting && !props.item.isCurrentUser && !props.item.seen_at) {
                    // console.log('Message visible:', props.item);
                    emit('onObserve', props.item)

                    if (el.value && observer) {
                        observer.unobserve(el.value)
                    }
                }
            });
        },
        { threshold: 0.1 },
    );
    if (el.value) {
        observer.observe(el.value);
    }
});

onBeforeUnmount(() => {
    if (observer && el.value) {
        observer.unobserve(el.value);
    }
});
</script>

<template>
    <div ref="el" style="width: 100%">
        <slot />
    </div>
</template>
