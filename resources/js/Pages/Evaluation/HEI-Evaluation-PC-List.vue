<template>
    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8">
        <div class="w-full p-2 md:p-5 my-3 md:shadow-lg md:border rounded-xl">
            <div class="flex flex-col gap-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div
                        class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                    >
                        <div class="w-full">
                            <p class="text-sm text-gray-500">HEI Name</p>
                            <div>
                                {{ program?.institution?.name }}
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                    >
                        <div class="w-full">
                            <p class="text-sm text-gray-500">Program</p>
                            {{ program?.program?.program }}
                            <span v-if="program?.program?.major">
                                -
                                {{ program?.program?.major }}</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <content-container :hideTopBorder="true">
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th>Academic Year</th>
                    <th>Status</th>
                    <th>
                        <i class="fas fa-ellipsis-v text-lg"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="program?.evaluation_form?.length == 0">
                        <no-search-result text="compliance evaluation tool" />
                    </tr>
                    <tr
                        v-else
                        v-for="item in program?.evaluation_form"
                        :key="item.id"
                    >
                        <td>
                            {{ item.effectivity }}
                        </td>
                        <td>
                            <status :text="item.status" />
                        </td>
                        <td>
                            <div class="flex justify-end gap-0">
                                <button
                                    :disabled="starting"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-24 text-white bg-blue-600 hover:bg-blue-700 tooltipForActions"
                                    @click="createTool(item.id)"
                                    v-show="item.status === 'Deployed'"
                                >
                                    Start now
                                </button>
                                <button
                                    v-show="item.status === 'In progress'"
                                    @click="edit(item.id)"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-blue-700 hover:bg-blue-100 tooltipForActions"
                                    data-tooltip="Edit"
                                >
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <button
                                    @click="view(item.id)"
                                    class="inline-flex items-center justify-center rounded-full h-10 w-10 text-green-700 hover:bg-green-100 tooltipForActions"
                                    data-tooltip="View"
                                    v-show="item.status != 'Deployed'"
                                >
                                    <i class="fas fa-eye text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps(["program"]);

const starting = ref(false);

function edit(tool) {
    router.get("/hei/evaluation/" + tool + "/edit");
}

function view(tool) {
    router.get("/hei/evaluation/" + tool + "/view");
}

function createTool(item) {
    router.post(
        "/hei/tool/create",
        { id: item },
        {
            onStart: () => {
                starting.value = true;
            },
            onFinish: () => {
                starting.value = false;
            },
            preserveState: false,
            preserveScroll: true,
            replace: true,
        }
    );
}
</script>
