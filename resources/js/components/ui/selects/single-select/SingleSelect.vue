<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { useVModel } from '@vueuse/core';
import { computed } from 'vue';
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/16/solid';
import { CheckIcon } from '@heroicons/vue/20/solid';

const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    options?: Array<{ id: string | number, name: string }>
    class?: HTMLAttributes['class']
}>();

const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
}>();

const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue
});


const options = computed(() => props.options ?? []);

const selected = computed({
    get() {
        return options.value.find(option => option.id === modelValue.value) ?? null;
    },
    set(option) {
        emits('update:modelValue', option?.id ?? '');
    }
});
</script>

<template>
    <Listbox as="div" v-model="selected">
        <div class="relative mt-1">
            <ListboxButton
                :class="cn(
                  'grid w-full cursor-default grid-cols-1 h-9 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 sm:text-sm/6',
                  'focus:border-ring focus:ring-ring/50 focus:ring-[3px]',
                  'border shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
                  'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
                  props.class,
                )">

                <span class="col-start-1 row-start-1 truncate pr-6">{{ selected?.name || 'Select...' }}</span>
                <ChevronUpDownIcon
                    class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                    aria-hidden="true" />
            </ListboxButton>

            <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                <ListboxOptions
                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-hidden sm:text-sm">
                    <ListboxOption as="template" v-for="option in options" :key="option.id" :value="option"
                                   v-slot="{ active, selected }">
                        <li
                            :class="[active ? 'bg-[#2E2E2E] text-white outline-hidden' : 'text-gray-900', 'relative cursor-default py-2 pr-9 pl-3 select-none']">
                            <span :class="[selected ? 'font-semibold' : 'font-normal', 'block truncate']">{{ option.name }}</span>

                            <span v-if="selected"
                                  :class="[active ? 'text-white' : 'text-[#2E2E2E]', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                <CheckIcon class="size-5" aria-hidden="true" />
                            </span>
                        </li>
                    </ListboxOption>
                </ListboxOptions>
            </transition>
        </div>
    </Listbox>

</template>
