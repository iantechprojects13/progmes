<template>
    <div ref="container" class="h-auto mx-3 md:mx-8 mt-8 pb-10 mb-5 bg-white p-5 rounded relative">
        <div class="flex flex-col md:flex-row justify-between">
            <div class="flex items-center">
                <div v-show="hasBackButton" class="mr-2">
                    <slot name="back-button"></slot>
                </div>
                <div class="tracking-tight font-bold text-lg text-gray-600">
                    {{ pageTitle }}
                </div>
                <div v-show="hasResultCount" class="tracking-tight font-bold text-lg text-gray-600">
                    <slot name="result-count"></slot>
                </div>
            </div>
            <div v-show="hasTopButton" class="mt-3 md:mt-0">
                <slot name="top-button"></slot>
            </div>
        </div>

        <div class="mt-5 w-full overflow-x-auto border-b border-gray-400 whitespace-nowrap" v-show="hasNavigation">
            <slot name="navigation"></slot>
        </div>
        <div v-show="hasSearch || hasFilters" class="w-full py-3 mt-3 flex flex-col md:flex-row justify-between">
            <div>
                <slot name="left-button"></slot>
            </div>
            <div v-show="hasSearch" class="w-full mr-1">
                <slot name="search"></slot>
            </div>
            <div v-show="hasFilters" class="flex flex-row items-center justify-end mt-5 md:mt-0">
                <slot name="options"></slot>
            </div>
        </div>
        <div class="overflow-x-auto pt-2">
            <slot name="main-content"></slot>
        </div>
        <div class="w-full">
            <Pagination v-if="data_list" :data_list="data_list" />
        </div>
    </div>
</template>

<script setup>

    defineProps([
        "pageTitle",
        "placeholder",
        "data_list",
        "hasAction",
        "hasPagination",
        "hasTopButton",
        "hasResultCount",
        "hasSearch",
        "hasFilters",
        "hasNavigation",
        "hasBackButton",
    ]);
</script>