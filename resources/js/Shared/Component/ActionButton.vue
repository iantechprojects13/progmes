<template>
    <button
      @click="handleClick"
      class="inline-flex items-center justify-center rounded-full h-10 w-10 tooltipForActions"
      :class="[{'text-gray-400 hover:bg-gray-100 disabled:cursor-not-allowed': isDisabled}, buttonClasses]"
      :disabled="isDisabled"
      :data-tooltip="tooltip"
    >
      <i :class="iconClasses"></i>
    </button>
</template>
  
  <script setup>
  import { computed } from "vue";
  
  const props = defineProps({
    type: {
      type: String,
      default: "edit",
      validator: (value) => [
        "edit", "view", "delete", "evaluate",
        "reject", "unlock", "lock", "unlock",
      ].includes(value),
    },
    isDisabled: {
      type: Boolean,
      default: false,
    }
  });
  
  const emit = defineEmits(['click']);
  
  const handleClick = () => {
    emit('click');
  };
  
  const buttonClasses = computed(() => {
    const typeMap = {
      edit: "text-blue-700 hover:bg-blue-100",
      view: "text-green-700 hover:bg-green-100",
      delete: "text-red-700 hover:bg-red-100",
      evaluate: "text-blue-700 hover:bg-blue-100",
      accept: "text-blue-700 hover:bg-blue-100",
      reject: "text-red-700 hover:bg-red-100",
      lock: "text-red-700 hover:bg-red-100",
      unlock: "text-blue-700 hover:bg-blue-100",
    };
    return typeMap[props.type] || typeMap.edit;
  });
  
  const iconClasses = computed(() => {
    const iconMap = {
      edit: "fas fa-edit text-lg",
      view: "fas fa-eye text-lg",
      delete: "fas fa-trash text-lg",
      evaluate: "fas fa-edit text-lg",
      reject: "fas fa-times text-lg",
      lock: "fas fa-lock text-lg",
      unlock: "fas fa-unlock text-lg",
    };
    return iconMap[props.type] || iconMap.edit;
  });
  
  const tooltip = computed(() => {
    const tooltipMap = {
      edit: "Edit",
      view: "View",
      delete: "Delete",
      evaluate: "Evaluate",
      reject: "Reject",
      lock: "Lock",
      unlock: "Unlock",
    };
    return tooltipMap[props.type] || tooltipMap.edit;
  });
  </script>
  