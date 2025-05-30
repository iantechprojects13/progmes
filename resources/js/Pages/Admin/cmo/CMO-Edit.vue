<template>
    <Head title="Edit CMO" />
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">Edit CMO</div>
                <dropdown-option position="right" v-show="!hasTool && cmo.status != 'Published'">
                    <template v-slot:button>
                        <button
                            class="flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                        >
                            Save
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                    </template>
                    <template v-slot:options>
                        <div class="w-48">
                            <button v-show="!hasTool"
                                class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
                                @click.prevent="saveAsDraft"
                            >
                                Save as draft
                            </button>
                            <button v-show="cmo.status != 'Published'"
                                class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
                                @click.prevent="publish"
                            >
                                Publish
                            </button>
                        </div>
                    </template>
                </dropdown-option>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="md:p-4">
                <div v-if="hasTool" class="p-4 mb-4 md:mb-0 text-sm text-yellow-800 bg-yellow-100 rounded-lg" role="alert">
                    <strong>Warning:</strong> This CMO is associated with a program compliance evaluation tool(s). Making major changes may affect its integration and could have significant implications. Please proceed with caution and ensure all changes are thoroughly reviewed.
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg border p-2 md:p-8 md:m-4 mb-12">
                <div class="flex flex-col gap-6">
                    <!-- First Row: Discipline and Program -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Discipline Section -->
                        <div
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                        >
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Discipline</p>
                                <select
                                    v-model="form.discipline"
                                    @change="updateData(); form.program = null;"
                                    class="mt-1 w-full p-2 h-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                >
                                    <option :value="null">
                                        Select Discipline
                                    </option>
                                    <option
                                        v-for="discipline in discipline_list"
                                        :key="discipline.id"
                                        :value="discipline.id"
                                    >
                                        {{ discipline.discipline }}
                                    </option>
                                </select>
                                <FormErrorMessage
                                    :message="$page.props.errors.discipline"
                                    theme="dark"
                                />
                            </div>
                        </div>

                        <!-- Program Section -->
                        <div
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                        >
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Program</p>
                                <select
                                    v-model="form.program"
                                    :disabled="!form.discipline"
                                    @change="updateData()"
                                    class="mt-1 w-full p-2 h-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                    :class="{
                                        ' cursor-not-allowed': !form.discipline,
                                    }"
                                >
                                    <option :value="null">
                                        Select Program
                                    </option>
                                    <option
                                        v-for="program in program_list"
                                        :key="program.id"
                                        :value="program.id"
                                        v-show="
                                            program.disciplineId ==
                                            form.discipline
                                        "
                                    >
                                        {{ program.program }}
                                        <span v-if="program.major != null">
                                            - {{ program.major }}</span
                                        >
                                    </option>
                                </select>
                                <FormErrorMessage
                                    :message="$page.props.errors.program"
                                    theme="dark"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Second Row: CMO No., Series, and Version -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- CMO No. -->
                        <div
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                        >
                            <div class="w-full">
                                <p class="text-sm text-gray-500">CMO No.</p>
                                <input
                                    @input="updateData()"
                                    id="number"
                                    type="number"
                                    placeholder="CMO No."
                                    v-model="form.number"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300"
                                />
                                <FormErrorMessage
                                    :message="$page.props.errors.number"
                                    theme="dark"
                                />
                            </div>
                        </div>

                        <!-- Series -->
                        <div
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                        >
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Series</p>
                                <input
                                    @input="updateData()"
                                    id="series"
                                    type="number"
                                    placeholder="Series"
                                    v-model="form.series"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300"
                                />
                                <FormErrorMessage
                                    :message="$page.props.errors.series"
                                    theme="dark"
                                />
                            </div>
                        </div>

                        <!-- Version -->
                        <div
                            class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all"
                        >
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Version</p>
                                <input
                                    @input="updateData()"
                                    id="version"
                                    type="number"
                                    placeholder="Version"
                                    v-model="form.version"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300"
                                />
                                <FormErrorMessage
                                    :message="$page.props.errors.version"
                                    theme="dark"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:p-5">
                <div class="p-3 md:p-5 border rounded-xl shadow-lg">
                    <content-table>
                        <template v-slot:table-head>
                            <th>Item No</th>
                            <th>
                                Area
                            </th>
                            <th>
                                Minimum Requirement
                            </th>
                            <th></th>
                        </template>

                        <template v-slot:table-body>
                            <tr
                                v-for="(item, index) in areaArray"
                                :key="index"
                                >
                                <td class="w-24 align-top">
                                    {{ index + 1 }}
                                </td>
                                <td>
                                    <tip-tap-editor
                                        v-model="form.area[index]"
                                        :hasSaveBtn="false"
                                        title="Area"
                                        @input="updateData(); getUpdatedRowIndex(index)"
                                        @update="update"
                                    />
                                </td>
                                <td>
                                    <tip-tap-editor
                                        v-model="form.minReq[index]"
                                        :hasSaveBtn="false"
                                        title="Minimum Requirement"
                                        @input="updateData(); getUpdatedRowIndex(index)"
                                        @update="update"
                                    />
                                </td>
                                <td></td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>

    <div>
        <div
            class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center"
            v-if="saving"
        >
            <i class="fas fa-circle-notch animate-spin mr-3"></i> Saving
            changes...
        </div>
        <div
            class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center"
            v-else-if="allChangesSaved"
        >
            <i class="fas fa-check text-green-500 mr-3"></i> All changes saved.
        </div>
        <div
            class="w-auto border border-gray-400 h-auto bg-white fixed z-[995] bottom-2 left-2 p-2 px-4 rounded flex items-center"
            v-else-if="hasChanges"
        >
            Changes unsaved!
        </div>
        <div v-else></div>
    </div>
</template>

<script setup>
// -------------------------------------------------------------------------------
import { ref, computed, onUnmounted, onMounted, onBeforeUnmount, reactive, inject } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------------
onMounted(() => {
    window.addEventListener("beforeunload", handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener("beforeunload", handleBeforeUnload);
    hasUnsavedChanges.value = false;
});

onBeforeUnmount(() => {
    if (autoSaveTimeout.value) {
        clearTimeout(autoSaveTimeout.value);
    }
});


// -------------------------------------------------------------------------------
const props = defineProps([
    "cmo",
    "discipline_list",
    "program_list",
    "hasTool",
]);

// -------------------------------------------------------------------------------
// const handleKeyDown = (event) => {
//     if (event.ctrlKey || event.metaKey) {
//         if (event.key === "s" || event.key === "S") {
//             event.preventDefault();
//             refs.saveBtn.value.click();
//         }
//     }
// };

const handleBeforeUnload = (event) => {
    if (hasUnsavedChanges.value) {
        event.returnValue = true;
    }
};


const saveBtn = ref([]);
const refs = { saveBtn };

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");
const hasChanges = ref(false);
const allChangesSaved = ref(false);
const autoSaveTimeout = ref(null);
const saving = ref(false);
const rowsWithUpdates = [];


// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
    hasChanges.value = true;
    allChangesSaved.value = false;

    // Clear any existing timeout
    if (autoSaveTimeout.value) {
        clearTimeout(autoSaveTimeout.value);
    }

    // Set a new timeout for 3 seconds
    autoSaveTimeout.value = setTimeout(() => {
        update();
    }, 3000);
};

const areaArray = computed(() => {
    let area = [];
    for (var i = 0; i < props.cmo.criteria.length; i++) {
        area.push(props.cmo.criteria[i]["area"]);
    }
    return area;
});

const minReqArray = computed(() => {
    let minreq = [];
    for (var i = 0; i < props.cmo.criteria.length; i++) {
        minreq.push(props.cmo.criteria[i]["minimumRequirement"]);
    }
    return minreq;
});

const form = reactive({
    id: ref(props.cmo.id),
    discipline:
        ref(props.cmo.disciplineId) !== null
            ? ref(props.cmo.disciplineId)
            : null,
    program:
        ref(props.cmo.programId) !== null ? ref(props.cmo.programId) : null,
    number: ref(props.cmo.number),
    series: ref(props.cmo.series),
    version: ref(props.cmo.version),
    area: areaArray.value,
    minReq: minReqArray.value,
    rows: ref(rowsWithUpdates),
});


// -------------------------------------------------------------------------------
function getUpdatedRowIndex(index) {
    if (!rowsWithUpdates.includes(index)) {
        rowsWithUpdates.push(index);
    }
}

// function update() {
//     router.post("/admin/CMOs/update", form, {
//         onSuccess: () => {
//             hasUnsavedChanges.value = false;
//         },
//         preserveState: true,
//         preserveScroll: true,
//         replace: true,
//     });
// }

const update = async () => {
    if (hasUnsavedChanges.value) {
        // Clear the timeout to prevent duplicate saves
        if (autoSaveTimeout.value) {
            clearTimeout(autoSaveTimeout.value);
            autoSaveTimeout.value = null;
        }

        saving.value = true;
        hasChanges.value = false;

        await axios
            .post("/admin/CMOs/update", form)
            .then((response) => {
                hasUnsavedChanges.value = false;
                saving.value = false;
                allChangesSaved.value = true;
                // Clear the rowsWithUpdates array after successful save
                rowsWithUpdates.length = 0;
            })
            .catch((error) => {
                console.error("Error updating evaluation:", error);
                saving.value = false;
            });
    }
};

function saveAsDraft() {
    router.post("/admin/CMOs/save-as-draft", form, {
        replace: true,
    });
}

function publish() {
    router.post("/admin/CMOs/save-and-publish", form, {
        replace: true,
    });
}
</script>

