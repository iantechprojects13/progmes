<template>
    <Head :title="institution.name" />
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">HEI Details</div>
                <div>
                    <button
                        @click="edit(institution.id)"
                        class="w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="md:p-5">
                <div
                    class="w-full p-2 md:p-8 my-3 md:shadow-lg md:border rounded-xl"
                >
                    <div class="flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Institution
                                    </p>
                                    <div>
                                        {{ institution.code }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">No.</p>
                                    <div>
                                        {{ institution.name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="text-gray-800 border-b border-gray-300 mx-8 mb-2"
            >
                <p class="relative top-3 bg-white pr-3 w-fit">Program List</p>
            </div>
            <div class="p-5">
                <div class="p-5 border rounded-xl shadow-lg">
                    <content-table>
                        <template v-slot:table-head>
                            <th>Program</th>
                            <th>
                                Major
                            </th>
                            <th></th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="institution.active_program.length === 0">
                                <no-search-result text="program" />
                            </tr>

                            <tr
                                v-else
                                v-for="
                                    program
                                 in institution.active_program"
                                :key="program.id">
                                <td>
                                    {{ program.program.program }}
                                </td>
                                <td>
                                    <span v-if="program.program?.major">{{
                                        program.program.major
                                    }}</span>
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
// ------------------------------------------------------------
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({layout: Layout});

// ------------------------------------------------------------
const props = defineProps(["institution"]);

// ------------------------------------------------------------
function edit(id) {
    router.get("/admin/higher-education-institutions/" + id + "/edit");
}
</script>

