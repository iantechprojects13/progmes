<template>
    <div>
        <div v-show="hasAdminPanel" class="md:sticky md:-top-14 z-60">
            <admin-panel/>
        </div>
        <div v-show="pageTitle" class="px-3 md:px-8 mt-5">
            <div class="flex flex-row items-center">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between my-3 w-full bg-white p-5 py-3 rounded-lg border border-gray-300">
                    <div class="tracking-tight whitespace-nowrap overflow-hidden overflow-ellipsis flex flex-row items-center h-10">
                        <i class="fas mr-5 text-gray-600"
                            :class="[{ 'fa-user': page == 'user' }, { 'fas fa-file': page == 'cmo' || page == 'tool' },
                            {'fa-institution': page == 'hei'}, {'fa-book' : page == 'program' || page == 'discipline'},
                            {'fa-edit' : page == 'evaluation'}, {'fa-check' : page == 'monitored'}
                            ]">
                        </i>
                        <div class=" font-bold">
                            {{ pageTitle }}
                        </div>
                    </div>
                    <div v-show="hasTopButton" class="mt-3 sm:mt-0">
                        <slot name="top-button"></slot>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-auto mx-3 mt-3 md:mx-8 bg-white border border-gray-300 rounded-lg relative" :class="{'mt-8': !pageTitle}">
            <div class="overflow-x-auto px-5 pt-1 justify-between border-b border-gray-300 whitespace-nowrap"
                v-show="hasNavigation">
                <slot name="navigation"></slot>
            </div>
            <div v-show="!pageTitle" class="w-full py-8 px-5 flex items-center h-12 border-b border-gray-300">
                <slot name="content-title"></slot>
            </div>
            <div class="w-full flex md:flex-row flex-col md:items-center px-5 pb-3 pt-5 relative"
                v-show="hasSearch || hasTopButton || hasFilters">
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
    </div>
    <div v-show="hasModal">
        <slot name="modal"></slot>
    </div>
</template>

<script setup>
import AdminPanel from './AdminPanel.vue';

    defineProps([
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
    ]);

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