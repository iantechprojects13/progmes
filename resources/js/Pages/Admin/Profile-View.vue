<template>
    <Head title="View Profile" />
    <AdminPanel />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div
                class="w-full flex flex-col md:flex-row items-center justify-between gap-4 md:gap-0"
            >
                <div class="flex flex-row items-center w-full md:w-auto">
                    <div class="font-bold">Profile Details</div>
                </div>

                <div
                    v-show="$page.props.auth.user.role == 'Super Admin'"
                    class="w-full md:w-auto flex flex-col md:flex-row gap-2"
                >
                    <button
                        v-show="hasDiscipline"
                        @click="toggleProgramAssignmentModal"
                        class="w-full md:w-auto h-10 px-4 bg-blue-600 text-white hover:bg-blue-700 rounded-lg shadow-md"
                    >
                        Edit Programs
                    </button>
                    <button
                        @click="toggleChangeRoleModal"
                        class="w-full md:w-auto h-10 px-4 bg-blue-600 text-white hover:bg-blue-700 rounded-lg shadow-md"
                    >
                        Change Role
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="m-3 p-6 rounded-lg shadow-lg border border-gray-300">
                <table
                    class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden"
                >
                    <tbody>
                        <tr class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-user mr-2"></i> Name
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                {{ profile.name }}
                            </td>
                        </tr>
                        <tr class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-envelope mr-2"></i> Email
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                {{ profile.email }}
                            </td>
                        </tr>
                        <tr class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-id-card mr-2"></i> Account Type
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                {{ profile.type }}
                            </td>
                        </tr>
                        <tr class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-user-tag mr-2"></i> Role
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                <span v-if="profile.role == 'Vice-President for Academic Affairs'">
                                    VPAA/Dean of Multiple Discipline
                                </span>
                                <span v-else>
                                    {{ profile.role }}
                                </span>
                            </td>
                        </tr>
                        <tr v-show="hasInstitution" class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-university mr-2"></i> HEI Name
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                {{ getInstitution }}
                            </td>
                        </tr>
                        <tr v-show="hasDiscipline" class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-b border-gray-300"
                            >
                                <i class="fas fa-book mr-2"></i> Discipline
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                <div
                                    v-for="(item, index) in disciplineArray"
                                    :key="index"
                                >
                                    {{ item }}
                                </div>
                            </td>
                        </tr>
                        <tr v-show="hasProgram" class="border-b">
                            <th
                                class="px-4 py-3 text-left text-gray-700 font-semibold bg-slate-100 border-gray-300"
                            >
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Program
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                {{ getProgram }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-show="hasDiscipline" class="mt-3">
                    <div class="text-gray-800 border-b border-gray-300 mb-2">
                        <p class="relative top-3 bg-white pr-3 w-fit">
                            Program Assignment
                        </p>
                    </div>
                    <div
                        class="shadow-md border border-gray-300 p-3 mt-5 rounded-lg"
                    >
                        <content-table>
                            <template v-slot:table-head>
                                <th>Program</th>
                                <th>Major</th>
                                <th></th>
                            </template>
                            <template v-slot:table-body>
                                <tr v-if="assignedPrograms.length === 0">
                                    <no-search-result text="program" />
                                </tr>

                                <tr
                                    v-else
                                    v-for="program in assignedPrograms"
                                    :key="program.id"
                                >
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
            </div>
        </template>
    </content-container>
    <modal
        :showModal="showModal"
        @close="toggleChangeRoleModal"
        title="Change User Role"
        height="short"
        width="lg"
    >
        <div class="text-gray-700">
            Are you sure you want to change this user's role? This will
            deactivate their current role, and they will need to register again
            to apply for a new role.
        </div>
        <template v-slot:custom-button>
            <button
                @click="changeRole"
                class="h-10 w-24 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
            >
                Confirm
            </button>
        </template>
    </modal>

    <modal
        :showModal="programAssignmentModal"
        title="Program Assignment"
        @close="toggleProgramAssignmentModal"
        width="4xl"
        class="antialiased"
    >
        <div>
            <input
                type="search"
                name="search"
                id="search"
                placeholder="Search programs"
                class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                v-model="search"
            />
            <div class="p-3 flex flex-row items-center">
                <input
                    type="checkbox"
                    id="selectAllPrograms"
                    class="mr-2 text-blue-500"
                    :checked="allProgramsSelected"
                    @change="toggleAllPrograms"
                />
                <label
                    class="cursor-pointer font-medium"
                    for="selectAllPrograms"
                >
                    Select All
                </label>
            </div>
        </div>
        <div
            v-if="program_list.length == 0"
            class="h-[18rem] border border-gray-300 rounded w-full"
        >
            <div class="w-auto p-5 text-gray-500">No programs found</div>
        </div>
        <div
            v-else
            class="overflow-y-auto h-[18rem] border border-gray-300 rounded w-full mx-auto p-3"
        >
            <div
                v-for="(program, index) in program_list"
                :key="program.id"
                class="p-2 border-b border-gray-300"
            >
                <div class="mr-2 flex flex-row items-center py-1">
                    <input
                        type="checkbox"
                        :id="'programCheck' + index"
                        class="mr-2 text-blue-500"
                        :checked="props.assignedProgramIds.includes(program.id)"
                        @change="addProgram(program.id, index)"
                        ref="programCheckbox"
                    />
                    <label class="cursor-pointer" :for="'programCheck' + index">
                        {{ program.program }}
                        <span v-if="program.major"> - {{ program.major }}</span>
                    </label>
                </div>
            </div>
        </div>

        <template #custom-button>
            <div class="flex justify-end space-x-2">
                <button @click="update"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg shadow-sm hover:bg-blue-600"
                >
                    Update
                </button>
            </div>
        </template>
    </modal>
</template>

<script setup>
import { computed, ref, reactive } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps([
    "profile",
    "roles",
    "isAdmin",
    "hasDiscipline",
    "hasInstitution",
    "hasProgram",
    "assignedPrograms",
    "assignedProgramIds",
    "programList",
]);

const programCheckbox = ref([]);
const search = ref("");

const refs = {
    programCheckbox,
};

const program_list = computed(() => {
    if (search.value === "") {
        return props.programList;
    }

    return props.programList.filter((program) => {
        return program.program
            .toLowerCase()
            .includes(search.value.toLowerCase());
    });
});

// Add these computed property and method to your script setup
const allProgramsSelected = computed(() => {
    return (
        program_list.value.length > 0 &&
        program_list.value.every((program) =>
            props.assignedProgramIds.includes(program.id)
        )
    );
});

function toggleAllPrograms() {
    if (allProgramsSelected.value) {
        // Deselect all programs
        program_list.value.forEach((program) => {
            const index = props.assignedProgramIds.indexOf(program.id);
            if (index !== -1) {
                props.assignedProgramIds.splice(index, 1);
            }
        });
    } else {
        // Select all programs
        program_list.value.forEach((program) => {
            if (!props.assignedProgramIds.includes(program.id)) {
                props.assignedProgramIds.push(program.id);
            }
        });
    }
}

const form = reactive({
    userId: ref(props.profile.id),
    programs: ref(props.assignedProgramIds),
});

const programAssignmentModal = ref(false);

function addProgram(id, indx) {
    let index = props.assignedProgramIds.indexOf(id);

    if (index === -1) {
        props.assignedProgramIds.push(id);
    } else {
        props.assignedProgramIds.splice(index, 1);
    }
}

const disciplineArray = computed(() => {
    let discipline_list = [];
    if (props.hasDiscipline) {
        for (var i = 0; i < props.roles.length; i++) {
            discipline_list.push(props.roles[i].discipline?.discipline);
        }
    }
    return discipline_list;
});

const getProgram = computed(() => {
    let program = "";
    if (props.hasProgram) {
        program = props.roles[0]?.program?.program;
    }
    return program;
});

const getInstitution = computed(() => {
    let institution = "";
    if (props.hasInstitution) {
        institution = props.roles[0]?.institution.name;
    }
    return institution;
});

const showModal = ref(false);

function toggleChangeRoleModal() {
    showModal.value = !showModal.value;
}

function toggleProgramAssignmentModal() {
    programAssignmentModal.value = !programAssignmentModal.value;
}

function changeRole() {
    router.post("/register/change-role", { userId: props.profile.id });
}

function update() {
    router.post('/es/program/update', form, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    })
}
</script>
