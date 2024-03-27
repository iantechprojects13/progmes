<template>
    <div v-show="hasBackButton || pageTitle" class="px-3 md:px-8 mt-5">
        <div class="flex flex-row items-center">
            <div v-show="hasBackButton" class="mr-2">
                <slot name="back-button"></slot>
            </div>
            <div class="flex items-center my-3">
                <div class="tracking-tight font-bold text-xl text-gray-700">
                    {{ pageTitle }}
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto mt-3 mx-3 md:mx-8 justify-between border-b-2 border-gray-400 whitespace-nowrap"
        v-show="hasNavigation">
        <slot name="navigation"></slot>
    </div>
    <div class="h-auto mx-3 md:mx-8 mt-8 pb-5 mb-5 bg-white rounded relative border border-gray-400">
        <div class="flex flex-col-reverse md:flex-row md:items-center p-3 py-4 border-b border-gray-300"
            v-show="hasSearch || hasTopButton || hasFilters">
            <div v-show="hasSearch || hasFilters"
                class="w-full flex flex-col-reverse md:flex-row justify-between mt-3 md:mt-0">
                <div v-show="hasSearch" class="w-full">
                    <slot name="search"></slot>
                </div>
                <div v-show="hasFilters" class="flex flex-row items-center justify-end mx-2">
                    <slot name="options"></slot>
                </div>
            </div>
            <div class="flex flex-row justify-between text-right">
                <div v-show="hasTopButton" class="w-fit flex items-center mr-2">
                    <slot name="top-button"></slot>
                </div>
            </div>
        </div>

        <div>
            <div class="overflow-x-auto pt-2">
                <slot name="main-content"></slot>
            </div>
            <div class="w-full p-5 flex justify-between xl:flex-row flex-col items-center border-t border-gray-300"
                v-if="data_list">
                <div class="flex items-center text-sm mb-5 xl:mb-0">
                    <slot name="show-item"></slot>
                </div>
                <Pagination v-if="data_list.links.length > 3 && data_list != null" :data_list="data_list" />
            </div>
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
        "hasSearch",
        "hasFilters",
        "hasNavigation",
        "hasBackButton",
    ]);

</script>

<script>
    export default {
        methods: {
            resultCount() {
                this.$emit('resultCount');
            }
        }
    }
</script>