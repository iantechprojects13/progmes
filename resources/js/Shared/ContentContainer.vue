<template>
    <div v-show="hasAdminPanel" class="md:sticky md:-top-20 z-60">
        <admin-panel/>
    </div>
    <div v-show="pageTitle" class="px-4 md:px-8 mt-8">
        <div class="flex flex-row items-center shadow-sm shadow-gray-200 rounded-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full bg-white rounded-xl border border-slate-100 p-6">
                <div class="font-semibold text-base md:text-lg text-slate-700 tracking-tight">
                    {{ pageTitle }}
                </div>
                <div v-show="hasTopButton" class="mt-4 sm:mt-0">
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
            <div v-show="!pageTitle" ref="actionButtons" class="w-full px-5 flex items-center h-auto" :class="{'border-b border-slate-200 py-4' : !hideTopBorder}">
                <slot name="content-title"></slot>
            </div>
            <div class="w-full flex md:flex-row flex-col md:items-center relative"
                v-show="hasSearch || hasTopButton || hasFilters"
                :class="{'px-5 pb-3 pt-5': hasSearch || hasTopButton || hasFilter}"
                >
                <div v-show="hasSearch || hasFilters"
                    class="w-full flex md:flex-row flex-col-reverse">
                    <div v-show="hasSearch" class="w-full md:w-1/2 my-3 md:my-auto">
                        <slot name="search"></slot>
                    </div>
                    <div class="w-full md:w-1/2 md:ml-5 flex flex-row items-center whitespace-nowrap justify-between">
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
]);


const showStickyDiv = () => {
    const rect = actionButtons.value.getBoundingClientRect();
    if (`${ rect.top }` < -80) {
        stickyDiv.value.classList.remove('hidden');
    } else {
        stickyDiv.value.classList.add('hidden');
    }
};

function showDivAfterLoad() {
    const rect = actionButtons.value.getBoundingClientRect();
    if (`${ rect.top }` < -80) {
        stickyDiv.value.classList.remove('hidden');
    } else {
        stickyDiv.value.classList.add('hidden');
    }
}

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
            }
        }
    }
</script>