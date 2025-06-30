<template>
  <div :class="['alert', statusClass]">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
  status: {
    type: String,
    default: 'info',
    validator: (val: string) => ['info', 'success', 'warning', 'error'].includes(val),
  },
});

const statusClass = computed(() => {
  switch (props.status) {
    case 'success':
      return 'alert-success';
    case 'warning':
      return 'alert-warning';
    case 'error':
      return 'alert-error';
    default:
      return 'alert-info';
  }
});
</script>

<style scoped>
.alert {
  padding: 1rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
  font-size: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.alert-info {
  background: #e5f6fd;
  color: #0369a1;
  border: 1px solid #bae6fd;
}
.alert-success {
  background: #ecfdf5;
  color: #047857;
  border: 1px solid #6ee7b7;
}
.alert-warning {
  background: #fef9c3;
  color: #b45309;
  border: 1px solid #fde68a;
}
.alert-error {
  background: #fee2e2;
  color: #b91c1c;
  border: 1px solid #fecaca;
}
</style>
