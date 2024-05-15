<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <div class="mx-3 md:mx-8 mt-8">
        <Link href="/admin/CMOs">
        <button class="select-none w-24 h-10 border border-gray-500 rounded bg-white hover:bg-gray-700 hover:text-white">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 border border-gray-400 bg-white rounded mt-5 p-5">
        <div>
            <div class="font-bold text-lg mb-3">CMO Details</div>
            <table>
                <tbody>
                    <tr>
                        <td class="px-3 text-right font-bold text-blue-500">No</td>
                        <td class="px-3">{{ cmo.number }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right font-bold text-blue-500">Series</td>
                        <td class="px-3">{{ cmo.series }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right font-bold text-blue-500">Version</td>
                        <td class="px-3">{{ cmo.version }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right font-bold text-blue-500">Program</td>
                        <td class="px-3">{{ cmo.program.program }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <content-container>
        <template v-slot:main-content>
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
                    <tr v-else v-for="(item, index) in cmo.criteria" :key="item.id" class="align-top"
                        :class="{'bg-slate-200' : index % 2 ==0}">
                        <td class="p-3">{{item.itemNo}}</td>
                        <td class="p-3 whitespace-normal">{{item.area}}</td>
                        <td class="p-3 whitespace-normal">{{item.minimumRequirement}}</td>
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