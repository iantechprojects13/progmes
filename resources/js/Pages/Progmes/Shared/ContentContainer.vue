<template>
    <div class="h-auto rounded-t my-3 mx-3 md:mx-8 text-sm sticky top-0">
        <div class="w-full flex flex-row justify-between items-center py-2 z-30">
            <div class="flex items-center justify-between w-full" :class="{ 'hidden': !hideSearch }">
                <div class="flex">
                    <slot name="channel"></slot>
                </div>
                <div class="pr-2 flex">
                    <slot name="actions"></slot>
                </div>
            </div>
            <div class="flex my-2 md:w-72" :class="{ ' justify-end w-full relative': !hideSearch }">
                <div class="relative md:block w-full" :class="{ 'hidden': hideSearch }">
                    <form>
                        <input type="search" id="content-search" :placeholder="placeholder"
                            class="rounded border w-full border-gray-400 pr-11 h-10 md:bg-white bg-blue-100 text-sm"
                            :class="{ '': !hideSearch }">
                        <button
                            class="rounded-full hover:bg-blue-300 active:bg-blue-600 active:text-white absolute top-0 right-0 h-10 w-10">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <button class="hover:bg-gray-200 rounded border h-10 w-10 border-gray-400 md:hidden"
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
        },

        handleResize() {
            if (window.innerWidth > 768) {
                this.hideSearch = true;
            }
        },
    },

    mounted() {
        window.addEventListener('resize', this.handleResize);
    },
}
</script>