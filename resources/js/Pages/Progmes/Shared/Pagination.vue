<template>
    <div class="select-none max-w-xs lg:max-w-full items-end overflow-auto">
        <div class="text-blue-500 flex items-center justify-center w-fit rounded">
            <Link v-for="link in data_list.links" :key="link.id" preserve-state
                :class="{ 'font-bold bg-blue-600 text-white': link.active }"
                class=" h-full flex items-center py-0.5 px-2" :href="link.url">
            <span class="whitespace-nowrap" v-html="link.label"></span>
            </Link>
        </div>
    </div>
</template>

<script setup>

    defineProps([
        'data_list',
    ]);

</script>