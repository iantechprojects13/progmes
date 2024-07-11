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
            <div class="border border-gray-300 mb-5 rounded mt-3 p-5 md:p-8">
                <table>
                    <tbody>
                        <tr>
                            <td class="px-1 py-0.5 align-text-top">No</td>
                            <td class="px-5 py-0.5 align-text-top">:</td>
                            <td class="px-1 py-0.5 align-text-top">
                                    {{ cmo.number }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-1 py-0.5 align-text-top">Series</td>
                            <td class="px-5 py-0.5 align-text-top">:</td>
                            <td class="px-1 py-0.5 align-text-top">
                                {{ cmo.series }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-1 py-0.5 align-text-top">Version</td>
                            <td class="px-5 py-0.5 align-text-top">:</td>
                            <td class="px-1 py-0.5 align-text-top">
                                {{ cmo.version }}
                            </td>
                        </tr>
                        <tr>
                            <td class="px-1 py-0.5 align-text-top">Program</td>
                            <td class="px-5 py-0.5 align-text-top">:</td>
                            <td class="px-1 py-0.5 align-text-top">
                                {{ cmo.program?.program }}
                                <span v-if="cmo.program?.major != null">
                                    - {{ cmo.program?.major }}
                                </span>
                            </td>
                        </tr>
                        <tr v-show="!$page.url.includes('/admin/CMOs/draft')">
                            <td class="px-1 py-0.5 align-text-top">Active Status</td>
                            <td class="px-5 py-0.5 align-text-top">:</td>
                            <td class="px-1 py-0.5 align-text-top text-xs">
                                <div v-if="cmo.isActive" class=" bg-green-500 text-white w-fit px-1 rounded">
                                    Active
                                </div>
                                <div v-else class="bg-gray-500 text-white w-fit px-1 rounded">
                                    Not Active
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-300">Item</th>
                    <th class="p-3 border-b border-gray-300">Area</th>
                    <th class="p-3 border-b border-gray-300">Minimum Requirement</th>
                    <th class="p-3 border-b border-gray-300">Test</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo.length == 0">
                        <td class="py-10" colspan="3"></td>
                    </tr>
                    <tr v-else v-for="(item, index) in cmo.criteria" :key="item.id" class="hover:bg-gray-200 align-top"
                        :class="{'bg-gray-100' : index % 2 == 1}">
                        <td class="p-3">{{item.itemNo}}</td>
                        <td class="p-3 whitespace-normal">{{item.area}}</td>
                        <td class="p-3 whitespace-normal">{{item.minimumRequirement}}</td>
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