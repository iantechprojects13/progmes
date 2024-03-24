<template>

    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <div class="mx-3 md:mx-8 mt-8">
        <Link href="/admin/CMOs">
        <button class="h-10 px-3 bg-white border-2 border-gray-500 rounded">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 bg-white rounded mt-5 p-5">
        <div>
            <div class="font-bold text-lg mb-3">CMO Details</div>
            <table class="">
                <tbody>
                    <tr>
                        <td class="px-3 text-right">No</td>
                        <td class="px-3">{{ cmo.number }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right">Series</td>
                        <td class="px-3">{{ cmo.series }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right">Version</td>
                        <td class="px-3">{{ cmo.version }}</td>
                    </tr>
                    <tr>
                        <td class="px-3 text-right">Program</td>
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
    <pre>
        {{ cmo }}
    </pre>
    <!-- 
    <content-container placeholder="Search row">
        <template v-slot:channel>
            <Link :href="route('admin.cmo.list')">
            <button class="py-1 px-4 h-10 rounded-full">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:actions>
            <button class="h-10 px-3 border rounded hover:text-black border-gray-400 text-gray-500 tooltipForActions"
                data-tooltip="Edit">
                <i class="fas fa-edit"></i>
            </button>
        </template>
        <template v-slot:content-head>
            <div class="p-3 shadow shadow-gray-400 m-3 mb-5">
                <div class="text-base font-bold text-gray-600">CMO Details</div>
                <div>
                    <div class="mt-2 flex flex-col lg:flex-row">
                        <div
                            class="w-full max-w-10 flex items-center lg:justify-end text-right mr-3 font-bold text-gray-600">
                            <label for="discipline">Discipline</label>
                        </div>
                        <input type="text" disabled id="number"
                            class="h-10 border-gray-300 text-sm rounded w-full max-w-md"
                            :value="cmo.discipline.discipline">
                    </div>
                    <div class="mt-2 flex flex-col lg:flex-row">
                        <div
                            class="w-full max-w-10 flex items-center lg:justify-end text-right mr-3 font-bold text-gray-600">
                            <label for="program">Program</label>
                        </div>
                        <input type="text" disabled id="number"
                            class="h-10 border-gray-300 text-sm rounded w-full max-w-md" :value="cmo.program.program">
                    </div>
                    <div class="mt-2 flex flex-col lg:flex-row">
                        <div
                            class="w-full max-w-10 flex items-center lg:justify-end text-right mr-3 font-bold text-gray-600">
                            <label for="number">No.</label>
                        </div>
                        <input type="text" disabled id="number"
                            class="h-10 border-gray-300 text-sm rounded w-full max-w-md" :value="cmo.number">
                    </div>
                    <div class="mt-2 flex flex-col lg:flex-row">
                        <div
                            class="w-full max-w-10 flex items-center lg:justify-end text-right mr-3 font-bold text-gray-600">
                            <label for="series">Series</label>
                        </div>
                        <input type="text" disabled id="series"
                            class="h-10 border-gray-300 text-sm rounded w-full max-w-md" :value="cmo.series">
                    </div>
                    <div class="mt-2 flex flex-col lg:flex-row">
                        <div
                            class="w-full max-w-10 flex items-center lg:justify-end text-right mr-3 font-bold text-gray-600">
                            <label for="version">Version</label>
                        </div>
                        <input type="text" disabled id="version"
                            class="h-10 border-gray-300 text-sm rounded w-full max-w-md" :value="cmo.version">
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-t border-gray-400">Item</th>
                    <th class="p-3 border-t border-gray-400">Area</th>
                    <th class="p-3 border-t border-gray-400">Minimum Requirement</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in cmo.criteria" class="border-b border-gray-400"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 align-top">
                            {{ item.itemNo }}
                        </td>
                        <td class="p-3 align-top">{{ item.area }}</td>
                        <td class="p-3 align-top whitespace-normal">{{ item.minimumRequirement }}</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    -->
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