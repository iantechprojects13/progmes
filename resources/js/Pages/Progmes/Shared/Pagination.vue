<template>
    <div v-if="data_list.links.length > 3" class="w-full py-5">
        <div class="h-10 text-gray-600 flex items-center justify-center">
            <Link v-for="link in data_list.links" :key="link.id"
                :class="{ 'font-bold text-white bg-blue-600': link.active }"
                class=" h-full flex items-center px-4 border border-collapse border-gray-400" :href="link.url"><span
                v-html="link.label"></span>
            </Link>
        </div>
    </div>
</template>

<script setup>

defineProps([
    'data_list',
]);

</script>