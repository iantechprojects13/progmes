<template>
    <div class="h-auto relative rounded-t">
        <div class="w-full flex flex-row justify-between text-sm items-center py-2 px-3 rounded-t-lg z-30 bg-white">
            <div class="flex items-center justify-between w-full pr-1 md:flex" :class="{ 'hidden': !hideSearch }">
                <div class="flex items-center my-2">
                    <slot name="channel"></slot>
                </div>
                <div>
                    <slot name="actions"></slot>
                </div>
            </div>
            <div class="flex my-2 md:w-72" :class="{ ' justify-end w-full relative': !hideSearch }">
                <div class="relative md:block w-full" :class="{ 'hidden': hideSearch }">
                    <form>
                        <input type="search" id="content-search" :placeholder="placeholder"
                            class="rounded text-sm border w-full border-gray-400 pr-10" :class="{ '': !hideSearch }">
                        <button class="rounded-full hover:bg-gray-200 absolute top-0 right-0 h-full px-3">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <button class="hover:bg-gray-200 w-10 p-2 px-3 rounded border border-gray-400 md:hidden"
                    :class="{ 'ml-1': !hideSearch }" @click="toggleSearch">
                    <i :class="[{ 'fas fa-search': hideSearch }, { 'fas fa-close': !hideSearch }]"></i>
                </button>
            </div>
        </div>
        <div class="overflow-auto max-h-screen">
            <div>
                <slot name="content"></slot>
            </div>
        </div>
    </div>
    <div class="w-full">
        <Pagination v-if="data_list" :data_list="data_list" />
    </div>
</template>

<script setup>
defineProps([
    'placeholder',
    'data_list',
]);
</script>

<script>
export default {
    data() {
        return {
            hideSearch: true,
        }
    },

    methods: {
        toggleSearch() {
            this.hideSearch = !this.hideSearch;
        }
    },
}
</script>