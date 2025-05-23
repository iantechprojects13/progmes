<template>
    <div class="md:p-3">
        <div class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl">
            <div class="flex flex-col gap-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div
                        v-for="(value, label, index) in data"
                        :key="index"
                        class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                    >
                        <div class="w-full">
                            <p class="text-sm text-gray-500">
                                {{ label }}
                            </p>
                            <div v-if="value != null">
                                {{ value }}
                            </div>
                            <div v-else>-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps(["data"]);
</script>
