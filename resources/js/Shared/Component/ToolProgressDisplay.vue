<template>
  <div class="px-2 mt-2 md:px-4 text-gray-600"><span v-if="type == 'HEI'">Self-</span>Evaluation Status</div>
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-2 md:px-4">
    <div
      v-for="(item, index) in statusItems"
      :key="index"
      :class="`p-4 rounded-lg shadow-md text-3xl font-bold text-${item.color}-600 bg-${item.color}-100 text-center`"
    >
      <div v-if="data[index] != null">
        {{ data[index] }}{{ item.suffix }}
      </div>
      <div v-else>
        -
      </div>
      <p class="font-semibold text-base">{{ item.label }}</p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps(["data", "type"]);
const statusItems = [
  { label: "Complied", color: "green", suffix: "" },
  { label: "Not Complied", color: "red", suffix: "" },
  { label: "Not Applicable", color: "gray", suffix: "" },
  { label: "Progress", color: "blue", suffix: "%" }
];
</script>
