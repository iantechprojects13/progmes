<template>
    <div v-if="data_list.links.length > 3" class="w-full items-center p-3 pt-5 border-t-2">
        <div class="h-10 text-gray-600 text-sm flex items-center justify-center">
            <Link v-for="link in data_list.links" :key="link.id" :class="{ 'font-bold text-blue-500': link.active }"
                class=" h-full flex items-center px-3" :href="link.url"><span v-html="link.label"></span>
            </Link>
        </div>
    </div>
</template>

<script setup>

defineProps([
    'data_list',
]);

</script>