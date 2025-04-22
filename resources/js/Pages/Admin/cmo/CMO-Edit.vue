<template>
    <Head title="Edit CMO" />
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">Edit CMO</div>
                <dropdown-option position="right">
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
                            <button
                                class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
                                ref="saveBtn"
                                @click.prevent="update"
                            >
                                Save changes
                            </button>
                            <button v-show="cmo.status != 'Published'"
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
            <!-- <div class="md:p-4">
                <div v-if="hasTool" class="p-4 mb-4 md:mb-0 text-sm text-yellow-800 bg-yellow-100 rounded-lg" role="alert">
                    <strong>Warning:</strong> This CMO is associated with a program compliance tool(s). Making major changes may affect its integration and could have significant implications. Please proceed with caution and ensure all changes are thoroughly reviewed.
                </div>
            </div> -->
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
                                    @change="form.program = null"
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
                                        @input="updateData"
                                        @update="update"
                                    />
                                </td>
                                <td>
                                    <tip-tap-editor
                                        v-model="form.minReq[index]"
                                        @input="updateData"
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
</template>

<script setup>
// -------------------------------------------------------------------------------
import { ref, computed, onUnmounted, onMounted, reactive, inject } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

// -------------------------------------------------------------------------------
onMounted(() => {
    window.addEventListener("keydown", handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener("keydown", handleKeyDown);
    hasUnsavedChanges.value = false;
});


// -------------------------------------------------------------------------------
const props = defineProps([
    "cmo",
    "discipline_list",
    "program_list",
    // "hasTool",
]);

// -------------------------------------------------------------------------------
const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === "s" || event.key === "S") {
            event.preventDefault();
            refs.saveBtn.value.click();
        }
    }
};
const saveBtn = ref([]);
const refs = { saveBtn };

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
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
});


// -------------------------------------------------------------------------------
function update() {
    router.post("/admin/CMOs/update", form, {
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

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

