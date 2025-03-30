<template>
    <div v-show="hasAdminPanel" class="md:sticky md:-top-18 z-60">
        <admin-panel/>
    </div>
    <div v-show="pageTitle" class="px-4 md:px-8 mt-5">
        <div class="flex flex-row items-center rounded-md">
            <div class="flex flex-col md:flex-row md:items-center justify-between w-full bg-white rounded-xl py-3 px-2">
                <div class="font-semibold text-base md:text-lg text-gray-700 tracking-tight">
                    {{ pageTitle }}
                </div>
                <div v-show="hasTopButton" class="mt-4 md:mt-0">
                    <div class="flex justify-end">
                        <slot name="top-button" class="w-full md:w-auto"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div v-show="hasStickyDiv" class="sticky md:top-0 py-3 top-16 bg-white shadow-lg z-50 hidden" ref="stickyDiv">
            <div class="w-full">
                <slot name="sticky-div"></slot>
            </div>
        </div>
        <div v-show="hasTopMainContent" class="mx-3 md:mx-8 mt-8 ">
            <slot name="top-main-content"></slot>
        </div>
        <div class="h-auto mx-3 mt-5 md:mx-8 bg-white border border-slate-200 rounded-lg relative" :class="{'mt-8': !pageTitle}">
            <div class="overflow-x-auto overflow-y-hidden px-5 pt-1 justify-between border-b border-slate-200 whitespace-nowrap"
                v-show="hasNavigation">
                <slot name="navigation"></slot>
            </div>
            <div v-show="!pageTitle" ref="actionButtons" class="w-full px-5 flex items-center text-gray-700 h-auto" :class="{'border-b border-slate-200 py-4' : !hideTopBorder}">
                <button v-show="hasBackButton" @click="goBack"
                class="w-8 h-8 mr-3 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left text-lg"></i></button>
                <slot name="content-title"></slot>
            </div>
            <div class="w-full flex lg:flex-row flex-col lg:items-center relative"
                v-show="hasSearch || hasTopButton || hasFilters"
                :class="{'px-5 pb-3 pt-5': hasSearch || hasTopButton || hasFilter}"
                >
                <div v-show="hasSearch || hasFilters"
                    class="w-full flex lg:flex-row flex-col-reverse">
                    <div v-show="hasSearch" class="w-full lg:w-1/2 my-3 lg:my-auto">
                        <slot name="search"></slot>
                    </div>
                    <div class="w-full lg:w-1/2 lg:ml-5 flex flex-row items-center whitespace-nowrap justify-between">
                        <div v-show="hasFilters" class="mr-2 flex flex-row">
                            <slot name="options"></slot>
                        </div>
                        <div v-if="data_list">
                            <paginator v-if="data_list.data.length != 0" :data_list="data_list" />
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="pt-0 md:pt-2 px-5 pb-5 overflow-auto">
                    <slot name="main-content"></slot>
                </div>
            </div>
        </div>
    <div v-show="hasModal">
        <slot name="modal"></slot>
    </div>
</template>

<script setup>
import AdminPanel from './AdminPanel.vue';
import { ref, onMounted, onUnmounted } from 'vue';

const stickyDiv = ref([]);
const actionButtons = ref([]);
const refs = { actionButtons, stickyDiv }

const props = defineProps([
    "pageTitle",
    "page",
    "placeholder",
    "data_list",
    "hasAdminPanel",
    "hasAction",
    "hasPagination",
    "hasTopButton",
    "hasSearch",
    "hasFilters",
    "hasNavigation",
    "hasModal",
    "hasStickyDiv",
    "hideTopBorder",
    "hasTopMainContent",
    "hasBackButton",
]);


const showStickyDiv = () => {
    const rect = actionButtons.value.getBoundingClientRect();
    if (`${ rect.top }` < -80) {
        stickyDiv.value.classList.remove('hidden');
    } else {
        stickyDiv.value.classList.add('hidden');
    }
};


onMounted(() => {
    window.addEventListener('scroll', showStickyDiv);
    setTimeout(showStickyDiv, 50);
});

onUnmounted(() => {
    window.removeEventListener('scroll', showStickyDiv);
});

</script>

<script>
    export default {
    components: { AdminPanel },
        methods: {
            resultCount() {
                this.$emit('resultCount');
            },
            goBack() {
                window.history.back();
            }
        },
    }
</script>