<template>
  <div
    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap"
    :class="{
      'bg-blue-800 text-blue-100 border border-blue-600': text === 'Submitted' || text === 'Monitored' || text === 'Applied' || text === 'Complied' || text === 'Not complied',
      'bg-green-800 text-green-100 border border-green-600': text === 1 || text === 'In progress',
      'bg-red-800 text-red-100 border border-red-600': text === 0 || text === 'Locked' || text === 'Not complied',
      'bg-gray-800 text-gray-100 border border-gray-600': text === 'For re-evaluation' || text === 'draft' || text === 'Not applicable',
    }"
  >
    {{ displayText }}
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  text: {
    type: String,
    required: true
  }
});

const displayText = computed(() => {
  const textMap = {
    'Submitted': 'Ready for visit',
    'Applied': 'Applied',
    'draft': 'Draft',
    'In progress': 'In progress',
    'Locked': 'Locked',
    'For re-evaluation': 'For re-evaluation',
    'Monitored': 'Monitored',
    1: 'Active',
    0: 'Inactive',
    'No status': 'No status',
    'Complied': 'Complied',
    'Not complied': 'Not complied',
    'Not applicable': 'Not applicable',

  };

  return textMap[props.text] || 'No status';
});
</script>