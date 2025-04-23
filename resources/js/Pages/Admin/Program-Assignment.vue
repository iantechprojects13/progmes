<template>
    <Head title="Program Assignment"/>
    <content-container :hasAdminPanel="true" :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col text-left md:flex-row items-center justify-between gap-2">
                <div class="font-bold w-full text-left">Program Assignment</div>
                <div class="w-full flex flex-col md:flex-row md:w-auto gap-2">
                    <button
                        @click="toggleDisciplineModal"
                        class="w-full md:w-auto h-10 px-4 bg-blue-600 text-white hover:bg-blue-700 rounded-lg shadow-md whitespace-nowrap"
                    >
                        <i class="fas fa-edit mr-2"></i> Edit Discipline
                    </button>
                    <button
                        @click="update"
                        class="w-full md:w-auto h-10 px-4 bg-blue-600 text-white hover:bg-blue-700 rounded-lg shadow-md whitespace-nowrap"
                    >
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="my-5 font-bold">
                {{ supervisor.name }}
            </div>
            <div>
                <input
                    type="search"
                    name="search"
                    id="search"
                    placeholder="Search programs"
                    class="mt-1 block w-full md:max-w-[32rem] rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                    v-model="search"
                />
                <div class="p-3 flex flex-row items-center mt-5">
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
                class="border border-gray-300 rounded w-full"
            >
                <div class="w-auto min-h-[18rem] p-5 text-gray-500">No programs found</div>
            </div>
            <div
                v-else
                class="overflow-y-auto border border-gray-300 rounded w-full mx-auto p-3"
            >
                <div
                    v-for="(program, index) in program_list"
                    :key="program.id"
                    class="p-2 border-b border-gray-300 select-none"
                    :class="{'bg-gray-100': index % 2 == 1}"
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
        </template>
    </content-container>
    
    <modal
        :showModal="disciplineModal"
        title="Edit Discipline"
        @close="toggleDisciplineModal"
        width="4xl"
        class="antialiased"
    >
        <div
            v-if="disciplineList.length == 0"
            class="h-[24rem] border border-gray-300 rounded w-full"
        >
            <div class="w-auto p-5 text-gray-500">No discipline found</div>
        </div>
        <div
            v-else
            class="overflow-y-auto h-[24rem] border border-gray-300 rounded w-full mx-auto p-3"
        >
            <div
                v-for="(discipline, index) in disciplineList"
                :key="discipline.id"
                class="p-2 border-b border-gray-300"
            >
                <div class="mr-2 flex flex-row items-center py-1">
                    <input
                        type="checkbox"
                        :id="'disciplineCheck' + index"
                        class="mr-2 text-blue-500"
                        :checked="props.assignedDisciplineIds.includes(discipline.id)"
                        @change="addDiscipline(discipline.id, index)"
                        ref="disciplineCheckbox"
                    />
                    <label class="cursor-pointer" :for="'disciplineCheck' + index">
                        {{ discipline.discipline }}
                    </label>
                </div>
            </div>
        </div>

        <template #custom-button>
            <div class="flex justify-end space-x-2">
                <button @click="updateDiscipline"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg shadow-sm hover:bg-blue-600"
                >
                    Update Discipline
                </button>
            </div>
        </template>
    </modal>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { router } from "@inertiajs/vue3";
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

const props = defineProps([
    "supervisor",
    "programList",
    "disciplineList",
    "assignedProgramIds",
    "assignedDisciplineIds",
]);


const programCheckbox = ref([]);
const disciplineCheckbox = ref([]);
const disciplineModal = ref(false);

const search = ref("");

const refs = {
    programCheckbox,
    disciplineCheckbox,
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


function toggleDisciplineModal() {
    disciplineModal.value = !disciplineModal.value;
}

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
    userId: ref(props.supervisor.id),
    programs: ref(props.assignedProgramIds),
});

const form2 = reactive({
    userId: ref(props.supervisor.id),
    disciplines: ref(props.assignedDisciplineIds),
});


function addProgram(id, indx) {
    let index = props.assignedProgramIds.indexOf(id);

    if (index === -1) {
        props.assignedProgramIds.push(id);
    } else {
        props.assignedProgramIds.splice(index, 1);
    }
}

function addDiscipline(id, indx) {
    let index = props.assignedDisciplineIds.indexOf(id);

    if (index === -1) {
        props.assignedDisciplineIds.push(id);
    } else {
        props.assignedDisciplineIds.splice(index, 1);
    }
}

function update() {
    router.post('/admin/settings/assignment/update', form, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    })
}

function updateDiscipline() {
    router.post('/admin/settings/assignment/update-discipline', form2, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    })
}

</script>