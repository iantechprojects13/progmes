<template>
    <Head :title="institution.name" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="w-full flex flex-row items-center">
                    <Link href="/admin/higher-education-institutions/">
                        <button
                            class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions"
                            data-tooltip="Back"
                        >
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </Link>
                    <div class="ml-3 font-bold">Edit HEI</div>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div
                class="w-full max-w-4xl mx-auto p-2 md:p-8 my-3 md:m-5 md:shadow-lg md:border rounded-xl"
            >
                <!-- Form Header -->
                <div class="mb-4 text-sm text-gray-600">
                    <span class="text-red-500">*</span>
                    <span class="italic">indicates required field</span>
                </div>

                <!-- Form Content -->
                <div class="space-y-6 w-full">
                    <div class="flex flex-col lg:flex-row w-full">
                        <!-- Code -->
                        <div class="lg:w-1/2 w-full">
                            <label
                                for="name"
                                class="block font-medium text-gray-700"
                            >
                                HEI Name
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                required
                                maxlength="255"
                                v-model="form.name"
                                type="text"
                                id="name"
                                placeholder="Enter name"
                                class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            />
                            <form-error-message
                                :message="$page.props.errors.name"
                                theme="dark"
                            />
                        </div>

                        <!-- Name -->
                        <div class="lg:ml-5 lg:w-1/2 w-full">
                            <label
                                for="code"
                                class="block font-medium text-gray-700"
                            >
                                HEI Code
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                required
                                maxlength="255"
                                v-model="form.code"
                                type="text"
                                id="code"
                                placeholder="Enter code"
                                class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            />
                            <form-error-message
                                :message="$page.props.errors.code"
                                theme="dark"
                            />
                        </div>
                    </div>

                    <!-- Programs Offered -->

                    <div>
                        <div class="text-sm mt-8">
                            Select programs offered by this institution
                        </div>
                        <input
                            type="search"
                            name="search"
                            id="search"
                            placeholder="Search programs"
                            class="mt-1 block w-full rounded-md h-12 border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            v-model="search"
                        />
                    </div>
                    <div
                        v-if="program_list.length == 0"
                        class="h-96 border border-gray-300 rounded w-full"
                    >
                        <div class="w-auto p-5 text-gray-500">
                            No programs found
                        </div>
                    </div>
                    <div
                        v-else
                        class="h-96 overflow-y-auto border border-gray-300 rounded w-full mx-auto p-3"
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
                                    :checked="
                                        props.programsOffered.includes(
                                            program.id
                                        )
                                    "
                                    @change="addProgram(program.id, index)"
                                    ref="programCheckbox"
                                />
                                <label
                                    class="cursor-pointer"
                                    :for="'programCheck' + index"
                                >
                                    {{ program.program }}
                                    <span v-if="program.major">
                                        - {{ program.major }}</span
                                    >
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button
                            @click="submit"
                            :disabled="processing"
                            class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Update
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";

const props = defineProps({
    institution: Object,
    program_list: Array,
    programsOffered: Array,
});

const processing = ref(false);
const programCheckbox = ref([]);

const refs = {
    programCheckbox,
    // selectAllCheckbox: ref(null)
};

const search = ref("");

const program_list = computed(() => {
    if (search.value === "") {
        return props.program_list;
    }

    return props.program_list.filter((program) => {
        return program.program
            .toLowerCase()
            .includes(search.value.toLowerCase());
    });
});

// const selectAll = () => {
//         const isChecked = refs.selectAllCheckbox.checked;
//         programCheckbox.value.forEach((checkbox, index) => {
//             checkbox.checked = isChecked;
//             if (isChecked) {
//                 if (!props.programsOffered.includes(props.program_list[index].id)) {
//                     props.programsOffered.push(props.program_list[index].id);
//                 }
//             } else {
//                 const programIndex = props.programsOffered.indexOf(props.program_list[index].id);
//                 if (programIndex !== -1) {
//                     props.programsOffered.splice(programIndex, 1);
//                 }
//             }
//         });
//     };

const form = reactive({
    id: ref(props.institution.id),
    code: ref(props.institution.code),
    name: ref(props.institution.name),
    programs: ref(props.programsOffered),
});

function submit() {
    router.post("/admin/higher-education-institutions/update", form, {
        onStart: () => {
            processing.value = true;
        },
        onFinish: () => {
            processing.value = false;
        },
        replace: true,
        preserveState: true,
    });
}

function addProgram(id, indx) {
    let index = props.programsOffered.indexOf(id);

    if (index === -1) {
        props.programsOffered.push(id);
    } else {
        props.programsOffered.splice(index, 1);
    }
}
</script>

<script>
import Layout from "@/Shared/Layout.vue";
export default {
    layout: Layout,
};
</script>
