<template>
    <div class="h-auto mx-3 md:mx-8 mt-8 pb-10 mb-5 bg-white p-5 rounded">
        <div class="flex flex-col md:flex-row justify-between">
            <div class="tracking-tight font-bold text-lg text-gray-600">
                {{ pageTitle }}
            </div>
            <div v-show="true" class="mt-3 md:mt-0">
                <slot name="top-button"></slot>
            </div>
        </div>
        <div class="mt-5 w-full overflow-x-auto border-b-2 border-gray-400 whitespace-nowrap" v-show="true">
            <slot name="navigation"></slot>
        </div>
        <div class="w-full py-3 mt-3 flex flex-col md:flex-row justify-between">
            <div class="w-full mr-0 md:mr-5">
                <slot name="search"></slot>
            </div>
            <div class="flex flex-row items-center justify-end mt-5 md:mt-0">
                <slot name="options"></slot>
            </div>
        </div>
        <div class="overflow-x-auto">
            <slot name="main-content"></slot>
        </div>
        <div class="w-full">
            <Pagination v-if="data_list" :data_list="data_list" />
        </div>
    </div>
    <!-- <div
        class="h-auto mb-5 mt-7 pt-0 mx-3 md:mx-8 rounded border border-gray-500 shadow shadow-gray-500 text-gray-700 text-sm">
        <div v-show="hasAction"
            class="w-full flex flex-row justify-between items-center bg-stone-100 text-gray-700 z-30 px-3 rounded-t py-3 sticky -top-0.5 border-b border-gray-400">
            <div class="flex items-center justify-between w-full" :class="{ hidden: !hideSearch }">
                <div class="flex">
                    <slot name="channel"></slot>
                </div>
                <div class="pr-2 flex">
                    <slot name="actions"></slot>
                </div>
            </div>
            <div v-show="placeholder" class="flex lg:w-80" :class="{ ' justify-end w-full relative': !hideSearch }">
                <div class="relative lg:block w-full" :class="{ hidden: hideSearch }">
                    <form>
                        <input type="search" id="content-search" :placeholder="placeholder"
                            class="rounded border w-full border-gray-500 pr-11 h-10 lg:bg-white bg-blue-200 text-base" />
                        <button
                            class="rounded-full hover:bg-blue-300 active:bg-blue-600 active:text-white absolute top-0 right-0 h-10 w-10">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <button
                    class="hover:bg-gray-200 rounded border h-10 w-12 border-gray-400 lg:hidden shadow shadow-gray-500"
                    :class="{ 'ml-1': !hideSearch }" @click="toggleSearch">
                    <i :class="[
                            { 'fas fa-search': hideSearch },
                            { 'fas fa-close': !hideSearch },
                        ]"></i>
                </button>
            </div>
        </div>
        <div class="bg-white">
            <slot name="content-head"></slot>
        </div>
        <div class="overflow-auto h-full bg-white rounded">
            <div>
                <slot name="content"></slot>
            </div>
        </div>
        <div>
            <slot name="content-foot"></slot>
        </div>

    </div> -->
</template>

<script setup>
    defineProps([
        "pageTitle",
        "placeholder",
        "data_list",
        "hasAction",
        "hasPagination",
    ]);
</script>