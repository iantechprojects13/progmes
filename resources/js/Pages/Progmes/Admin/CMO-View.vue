<template>

    <Head title="CMO Details" />
    
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="w-full flex flex-row items-center">
                    <Link :href="$page.url.includes('/admin/CMOs/draft') ? '/admin/CMOs/draft' : '/admin/CMOs/'">
                    <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                    </Link>
                    <div class="ml-3 font-bold">CHED Memorandum Order</div>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="border border-gray-400 mb-5 rounded mt-3 p-5 md:p-8 bg-gray-100 shadow-md">
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="w-full md:w-1/2">
                        <div class="font-semibold text-gray-700">No:</div>
                        <div class="text-gray-900">{{ cmo.number }}</div>
                    </div>
                    <div class="w-full md:w-1/2 mt-3 md:mt-0">
                        <div class="font-semibold text-gray-700">Series:</div>
                        <div class="text-gray-900">{{ cmo.series }}</div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row md:items-center mt-3">
                    <div class="w-full md:w-1/2">
                        <div class="font-semibold text-gray-700">Version:</div>
                        <div class="text-gray-900">{{ cmo.version }}</div>
                    </div>
                    <div class="w-full md:w-1/2 mt-3 md:mt-0">
                        <div class="font-semibold text-gray-700">Program:</div>
                        <div class="text-gray-900">
                            {{ cmo.program?.program }}
                            <span v-if="cmo.program?.major != null">
                                - {{ cmo.program?.major }}
                            </span>
                        </div>
                    </div>
                </div>
                <div v-show="!$page.url.includes('/admin/CMOs/draft')" class="flex flex-col md:flex-row md:items-center mt-3">
                    <div class="w-full md:w-1/2">
                        <div class="font-semibold text-gray-700">Active Status:</div>
                        <div class="text-xs">
                            <div v-if="cmo.isActive" class="bg-green-500 text-white w-fit px-2 py-1 rounded">
                                Active
                            </div>
                            <div v-else class="bg-gray-500 text-white w-fit px-2 py-1 rounded">
                                Not Active
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-300">Item</th>
                    <th class="p-3 border-b border-gray-300">Area</th>
                    <th class="p-3 border-b border-gray-300">Minimum Requirement</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo.length == 0">
                        <td class="py-10" colspan="3"></td>
                    </tr>
                    <tr v-else v-for="(item, index) in cmo.criteria" :key="item.id" class="hover:bg-gray-200 align-top"
                        :class="{'bg-gray-100' : index % 2 == 1}">
                        <td class="p-3">{{item.itemNo}}</td>
                        <td class="p-3 whitespace-normal">{{item.area}}</td>
                        <td class="p-3 whitespace-normal">
                            <div v-html="item.minimumRequirement"></div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>



<script setup>
    defineProps([
        'cmo',
    ]);
</script>

<script>
    import Layout from '../Shared/Layout.vue'
    export default {
        layout: Layout,
    }
</script>