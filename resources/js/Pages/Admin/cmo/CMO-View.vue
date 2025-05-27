<template>
    <Head title="CMO Details" />

    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">CHED Memorandum Order</div>
                <div v-show="canEdit">
                    <button
                        @click="edit"
                        class="w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <info-card :data="{
                'Program': cmo.program?.program + (cmo.program?.major ? ' - ' + cmo.program?.major : ''),
                'Number': cmo.number || '-',
                'Series': cmo.series || '-',
                'Version': cmo.version || '-',
            }
            "/>
            <div class="md:p-5">
                <div class="p-3 md:p-5 border rounded-xl shadow-lg">
                    <content-table>
                        <template v-slot:table-head>
                            <th>Item</th>
                            <th>Area</th>
                            <th>Minimum Requirement</th>
                            <th></th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="cmo.criteria == 0">
                                <no-search-result text="items" />
                            </tr>
                            <tr
                                v-else
                                v-for="(item, index) in cmo.criteria"
                                :key="item.id"
                                class="hover:bg-gray-200 align-top"
                                :class="{ 'bg-gray-100': index % 2 == 1 }"
                            >
                                <td>{{ item.itemNo }}</td>
                                <td>
                                    <div v-html="item.area"></div>
                                </td>
                                <td>
                                    <div v-html="item.minimumRequirement"></div>
                                </td>
                                <td></td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
// -------------------------------------------------------------------------------
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------------
const props = defineProps([
    "cmo",
    "canEdit",
]);

// -------------------------------------------------------------------------------
function edit() {
    router.get(`/admin/CMOs/draft/${props.cmo.id}/edit/`);
}
</script>

<style scoped>
a {
    color: rgb(65, 138, 255);
}
</style>
