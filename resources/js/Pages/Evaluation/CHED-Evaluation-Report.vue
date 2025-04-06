<template>
    <Head title="Generate Report" />
    <page-title title="Create Report" />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div
                class="w-full flex flex-col xl:flex-row items-center justify-between"
            >
                <div class="w-full flex flex-row items-center">
                    
                    <div class="font-bold">Generate Report</div>
                </div>
                <div
                    class="w-full xl:w-auto flex flex-col md:flex-row items-center xl:mt-0 mt-3"
                >
                    <div
                        class="flex flex-row items-center w-full md:w-auto mt-3 md:mt-0"
                    >
                        <button
                            @click="toggleOptionsModal"
                            class="w-24 h-10 mx-2 text text-white bg-blue-600 hover:bg-blue-700 rounded"
                        >
                            Options
                        </button>
                        <button
                            ref="saveBtn"
                            @click="generate"
                            class="select-none bg-blue-600 hover:bg-blue-700 text-sm font-medium text-white w-20 h-10 rounded"
                            :disabled="generatingReport"
                        >
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:top-button>
            <button
                @click="generate"
                class="select-none bg-blue-500 hover:bg-blue-600 text-white px-3 h-10 rounded"
                :disabled="generatingReport"
            >
                Save
            </button>
        </template>
        <template v-slot:main-content>
            <div class="p-5">
                <div
                    class="w-full p-2 md:p-8 my-3 md:shadow-lg md:border rounded-xl"
                >
                    <div class="flex flex-col gap-6">
                        <!-- First Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Institution Name -->
                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        HEI Name
                                    </p>
                                    <div>
                                        {{
                                            tool.institution_program.institution
                                                .name
                                        }}
                                    </div>
                                </div>
                            </div>

                            <!-- Program -->
                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">Program</p>
                                    <div>
                                        {{
                                            tool.institution_program.program
                                                .program
                                        }}
                                        <span
                                            v-if="
                                                tool.institution_program.program
                                                    .major != null
                                            "
                                        >
                                            -
                                            {{
                                                tool.institution_program.program
                                                    .major
                                            }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2nd Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Academic Year -->
                            <div
                                class="flex items-center p-4 rounded-lg bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Effectivity
                                    </p>
                                    <div>
                                        {{ "A.Y. " + tool.effectivity }}
                                    </div>
                                </div>
                            </div>

                            <!-- Date of Evaluation -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Evaluation Date
                                    </p>
                                    <div>
                                        <input
                                            type="date"
                                            id="evaluationDate"
                                            class="w-full rounded-lg border-gray-300 text-gray-700"
                                            placeholder="Monitoring/Evaluation Date"
                                            v-model="report.evaluationDate"
                                            @input="updateData"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 text-gray-500">Signatories</div>
                        <!-- 3rd Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Conforme 1 -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Conforme
                                    </p>
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Name"
                                        v-model="report.conforme1"
                                        @input="updateData"
                                    />
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Title"
                                        v-model="report.conforme1Title"
                                        @input="updateData"
                                    />
                                </div>
                            </div>

                            <!-- Conforme 2 -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Conforme
                                    </p>
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Name"
                                        v-model="report.conforme2"
                                        @input="updateData"
                                    />
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Title"
                                        v-model="report.conforme2Title"
                                        @input="updateData"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- 4th Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Evaluated by -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Evaluated by
                                    </p>
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Name"
                                        v-model="report.evaluatedBy"
                                        @input="updateData"
                                    />
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Title"
                                        v-model="report.evaluatedByTitle"
                                        @input="updateData"
                                    />
                                </div>
                            </div>

                            <!-- Reviewed by -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Reviewed by
                                    </p>
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Name"
                                        v-model="report.reviewedBy"
                                        @input="updateData"
                                    />
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Title"
                                        v-model="report.reviewedByTitle"
                                        @input="updateData"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- 5th Row -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Noted by -->
                            <div
                                class="flex items-center p-4 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all"
                            >
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">
                                        Noted by
                                    </p>
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Name"
                                        v-model="report.notedBy"
                                        @input="updateData"
                                    />
                                    <input
                                        maxlength="255"
                                        type="text"
                                        class="w-full h-12 border border-gray-300 rounded-lg my-1 bg-white text-gray-700"
                                        placeholder="Title"
                                        v-model="report.notedByTitle"
                                        @input="updateData"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <modal
        :showModal="showOptionsModal"
        @close="toggleOptionsModal"
        width="md"
        height="long"
        title="Report Options"
        :hasNoCancelButton="true"
    >
        <div class="w-full items-center md:mr-2 mt-3 md:mt-0">
            <!-- <div>
                <p class="text-sm text-gray-600">Paper</p>
                <div class="mt-2">
                    <label class="mr-2">
                        <input
                            type="radio"
                            v-model="submitReport.orientation"
                            value="portrait"
                            class="mr-1"
                        />
                        Portrait
                    </label>
                    <label>
                        <input
                            type="radio"
                            v-model="submitReport.orientation"
                            value="landscape"
                            class="mr-1"
                        />
                        Landscape
                    </label>
                </div>
            </div> -->
            <div class="flex flex-col space-y-2">
                <label for="show" class="text-sm font-medium text-gray-700">
                    Report Type
                </label>
                <select
                    v-model="submitReport.type"
                    id="show"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="monitoring">Monitoring/Evaluation</option>
                    <option value="deficiency">Deficiency</option>
                </select>
            </div>
            <div class="my-5">
                <!-- Preview Button -->
            </div>
        </div>
        <template v-slot:custom-button>
            <button
                @click="previewFile"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-colors duration-200 whitespace-nowrap"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M12 7C14.76 7 17 9.24 17 12C17 14.76 14.76 17 12 17C9.24 17 7 14.76 7 12C7 9.24 9.24 7 12 7ZM12 9C10.34 9 9 10.34 9 12C9 13.66 10.34 15 12 15C13.66 15 15 13.66 15 12C15 10.34 13.66 9 12 9Z"
                        fill="currentColor"
                    />
                    <path
                        d="M12 5C16.79 5 21.17 8.13 23 12C21.17 15.87 16.79 19 12 19C7.21 19 2.83 15.87 1 12C2.83 8.13 7.21 5 12 5ZM12 3C6.48 3 1.54 6.95 0 12C1.54 17.05 6.48 21 12 21C17.52 21 22.46 17.05 24 12C22.46 6.95 17.52 3 12 3Z"
                        fill="currentColor"
                    />
                </svg>
                Preview
            </button>

            <!-- Download Button -->
            <button
                @click="downloadFile"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-colors duration-200 whitespace-nowrap"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    viewBox="0 0 24 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"
                        fill="currentColor"
                    />
                </svg>
                Download
            </button>
            <button
                @click="showOptionsModal = false"
                class="text-white bg-green-600 hover:bg-green-700 w-20 rounded-lg"
            >
                Done
            </button>
        </template>
    </modal>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, inject } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps(["tool", "signatories"]);

const showOptionsModal = ref(false);

function toggleOptionsModal() {
    showOptionsModal.value = !showOptionsModal.value;
}

function previewFile() {
    const url =
        submitReport.type === "monitoring"
            ? `/report/monitoring/${props.tool.id}/${submitReport.orientation}/view`
            : `/report/deficiency/${props.tool.id}/${submitReport.orientation}/view`;
    window.open(url, "_blank");
}

function downloadFile() {
    const url =
        submitReport.type === "monitoring"
            ? `/report/monitoring/${props.tool.id}/${submitReport.orientation}/download`
            : `/report/deficiency/${props.tool.id}/${submitReport.orientation}/download`;
    window.open(url, "_blank");
}

const saveBtn = ref(null);
const refs = { saveBtn };

const hasUnsavedChanges = inject("hasUnsavedChanges");

const updateData = () => {
    hasUnsavedChanges.value = true;
};

const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === "s" || event.key === "S") {
            event.preventDefault();
            refs.saveBtn.value.click();
        }
    }
};

const handleBeforeUnload = (event) => {
    if (hasUnsavedChanges.value) {
        event.returnValue = true;
    }
};

onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
    window.addEventListener("beforeunload", handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
    window.removeEventListener("beforeunload", handleBeforeUnload);
    hasUnsavedChanges.value = false;
});

const report = reactive({
    id: ref(props.tool.id),
    evaluationDate: ref(props.tool.evaluationDate),
    conforme1: ref(props.tool.conforme1),
    conforme1Title: ref(props.tool.conforme1Title),
    conforme2: ref(props.tool.conforme2),
    conforme2Title: ref(props.tool.conforme2Title),
    evaluatedBy: ref(props.tool.evaluatedBy),
    evaluatedByTitle: ref(props.tool.evaluatedByTitle),
    reviewedBy: props.tool.reviewedBy ? props.tool.reviewedBy : props.signatories.reviewedBy,
    reviewedByTitle: props.tool.reviewedByTitle ? props.tool.reviewedByTitle : props.signatories.reviewedByTitle,
    notedBy: props.tool.notedBy ? props.tool.notedBy : props.signatories.notedBy,
    notedByTitle: props.tool.notedByTitle ? props.tool.notedByTitle : props.signatories.notedByTitle,
});

const generatingReport = ref(false);

const submitReport = reactive({
    type: "monitoring",
    orientation: "landscape",
    id: ref(props.tool),
});

function generate() {
    router.post("/report/generate", report, {
        onStart: () => {
            generatingReport.value = true;
        },
        onFinish: () => {
            generatingReport.value = false;
        },
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
}
</script>
