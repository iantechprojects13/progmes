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
            <info-card
                :data="{
                    'Institution Name': institution.name,
                    'Institutional Code': institution.code,
                }"
            />
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
const props = defineProps([
    "institution"
]);

// ------------------------------------------------------------
function edit(id) {
    router.get("/admin/higher-education-institutions/" + id + "/edit");
}
</script>

