<template>
  <div class="flex flex-col">
    <div class="h-5 w-32 bg-gray-400 rounded-lg relative overflow-hidden">
      <div 
        class="absolute left-0 h-full bg-blue-500 transition-all duration-1000 ease-out"
        :style="{ width: `${animatedPercentage}%` }"
      ></div>
      <span class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white">
        {{ Math.round(animatedPercentage) }}%
      </span>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed, onMounted } from 'vue'

const props = defineProps({
  percentage: {
    type: Number,
    required: true,
    validator: (value) => !isNaN(value)
  }
})

const currentPercentage = ref(0)
const animatedPercentage = ref(0)

const clampedPercentage = computed(() => {
  return Math.min(Math.max(currentPercentage.value, 0), 100)
})

watch(() => props.percentage, (newPercentage) => {
  currentPercentage.value = newPercentage
}, { immediate: true })

onMounted(() => {
  // Small delay to ensure the zero state is visible
  setTimeout(() => {
    animatedPercentage.value = clampedPercentage.value
  }, 100)
})
</script>