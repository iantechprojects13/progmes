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
            <div class="bg-gray-200 p-0.5 rounded-lg shadow-lg mt-5 mb-8">
                <table class="w-full border rounded-lg overflow-hidden shadow-md bg-white">
                    <tbody>
                        <tr class="border-b">
                            <th class="text-left align-top w-1/4 px-6 py-3 text-gray-700 font-bold">
                                No
                            </th>
                            <td class="px-6 py-3 text-gray-900">{{ cmo.number || '-' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left align-top w-1/4 px-6 py-3 text-gray-700 font-bold">
                                Series
                            </th>
                            <td class="px-6 py-3 text-gray-900">{{ cmo.series || '-' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left align-top w-1/4 px-6 py-3 text-gray-700 font-bold">
                                Version
                            </th>
                            <td class="px-6 py-3 text-gray-900">{{ cmo.version || '-' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left align-top w-1/4 px-6 py-3 text-gray-700 font-bold">
                                Program
                            </th>
                            <td class="px-6 py-3 text-gray-900">
                                {{ cmo.program?.program || '-' }}
                                <span v-if="cmo.program?.major != null">
                                    - {{ cmo.program?.major }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 bg-gray-300">Item</th>
                    <th class="p-3 bg-gray-300">Area</th>
                    <th class="p-3 bg-gray-300">Minimum Requirement</th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="cmo.length == 0">
                        <td class="py-10" colspan="3"></td>
                    </tr>
                    <tr v-else v-for="(item, index) in cmo.criteria" :key="item.id" class="hover:bg-gray-200 align-top"
                        :class="{'bg-gray-100' : index % 2 == 1}">
                        <td class="p-3">{{item.itemNo}}</td>
                        <td class="p-3 whitespace-normal">
                            <div v-html="item.area"></div>
                        </td>
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
    import Layout from '@/Shared/Layout.vue'
    export default {
        layout: Layout,
    }
</script>

<style>
a {
    color: blue;
}

</style>