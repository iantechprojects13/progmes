<template>
    <div v-if="data_list.links.length > 3" class="  bg-white py-2 my-1 rounded select-none">
        <div class="h-10 text-gray-600 flex items-center justify-center w-fit">
            <Link v-for="link in data_list.links" :key="link.id"
                :class="{ 'font-bold underline text-blue-500': link.active }"
                class=" h-full flex items-center px-2 border-collapse" :href="link.url"><span v-html="link.label"></span>
            </Link>
        </div>
    </div>
</template>

<script setup>

defineProps([
    'data_list',
]);

</script>