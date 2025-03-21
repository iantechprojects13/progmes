<template>
    <Head title="CMO Details" />

    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="w-full flex flex-row items-center">
                    <Link
                        :href="
                            $page.url.includes('/admin/CMOs/draft')
                                ? '/admin/CMOs/draft'
                                : '/admin/CMOs/'
                        "
                    >
                        <button
                            class="w-8 h-8 rounded-full text-gray-700 hover:bg-gray-200 tooltipForActions"
                            data-tooltip="Back"
                        >
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </Link>
                    <div class="ml-3 font-bold">CHED Memorandum Order</div>
                </div>
                <div v-show="cmo.status == 'draft'">
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
            <div class="p-5">
            <div
                class="w-full p-2 md:p-8 my-3 md:shadow-lg md:border rounded-xl"
            >
            <div class="flex flex-col gap-6">
                <!-- First Row:  Program and Number -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Program Section -->
                    <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                        <div class="w-full">
                            <p class="text-sm text-gray-500">Program</p>
                            <div>
                                {{ cmo.program?.program || "-" }}
                            <span v-if="cmo.program?.major != null">
                                - {{ cmo.program?.major }}
                            </span>
                            </div>
                        </div>
                    </div>
        
                    <!-- Number Section -->
                    <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                        <div class="w-full">
                            <p class="text-sm text-gray-500">No.</p>
                            <div>
                                {{ cmo.number || "-" }}
                            </div>
                            <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                        </div>
                    </div>
                </div>
        
                <!-- Series, and Version -->
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Series Section -->
                    <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                        <div class="w-full">
                            <p class="text-sm text-gray-500">Series</p>
                            <div>
                                {{ cmo.series || '-'}}
                            </div>
                        </div>
                    </div>
        
                    <!-- Version Section -->
                    <div class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all">
                        <div class="w-full">
                            <p class="text-sm text-gray-500">Version</p>
                            <div>
                                {{ cmo.version || "-" }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        </div>
        <div class="p-5">
            <div class="p-5 border rounded-xl shadow-lg">
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
                        <tr
                            v-else
                            v-for="(item, index) in cmo.criteria"
                            :key="item.id"
                            class="hover:bg-gray-200 align-top"
                            :class="{ 'bg-gray-100': index % 2 == 1 }"
                        >
                            <td class="p-3">{{ item.itemNo }}</td>
                            <td class="p-3 whitespace-normal">
                                <div v-html="item.area"></div>
                            </td>
                            <td class="p-3 whitespace-normal">
                                <div v-html="item.minimumRequirement"></div>
                            </td>
                        </tr>
                    </template>
                </content-table>
            </div>
    </div>
        </template>
    </content-container>
</template>

<script setup>
import { router } from "@inertiajs/vue3";

const props = defineProps(["cmo"]);

function edit() {
    router.get(`/admin/CMOs/draft/${props.cmo.id}/edit/`);
}
</script>

<script>
import Layout from "@/Shared/Layout.vue";
export default {
    layout: Layout,
};
</script>

<style scoped>
a {
    color: blue;
}
</style>
