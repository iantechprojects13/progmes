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
            <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-xl p-6 mt-5 mb-8">
    <!-- Header Card Design -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl p-4 mb-6">
        <h2 class="text-white text-xl font-semibold">Program Details</h2>
    </div>

    <!-- Content Grid - Two Items Per Row -->
    <div class="flex flex-wrap -mx-3">
        <!-- Number -->
        <div class="w-1/2 px-3 mb-6">
            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all h-full">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 rounded-lg">
                        <i class="fas fa-hashtag text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Number</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ cmo.number || '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Series -->
        <div class="w-1/2 px-3 mb-6">
            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all h-full">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 rounded-lg">
                        <i class="fas fa-layer-group text-indigo-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Series</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ cmo.series || '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Version -->
        <div class="w-1/2 px-3 mb-6">
            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all h-full">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-purple-100 rounded-lg">
                        <i class="fas fa-code-branch text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Version</h3>
                    <p class="text-lg font-semibold text-gray-900">{{ cmo.version || '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Program -->
        <div class="w-1/2 px-3 mb-6">
            <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all h-full">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 flex items-center justify-center bg-green-100 rounded-lg">
                        <i class="fas fa-graduation-cap text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Program</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ cmo.program?.program || '-' }}
                        <span v-if="cmo.program?.major != null" class="text-gray-600">
                            - {{ cmo.program?.major }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
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