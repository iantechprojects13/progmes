<template>

    <Head :title="institution.name" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
                <div class="w-full flex flex-row items-center justify-between">
                    <div class="w-full flex flex-row items-center">
                        <Link href="/admin/higher-education-institutions/">
                        <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                        </Link>
                        <div class="ml-3 font-bold">Edit HEI</div>
                    </div>
                </div>
        </template>
        <template v-slot:main-content>
            <div class="px-5 py-7 mx-auto lg:shadow lg:shadow-gray-500 lg:border lg:border-gray-500 rounded max-w-xl my-8">
                <div class="flex flex-col justify-center">
                    <div>
                        <span class="text-red-500">*</span>
                        <span class="italic text-sm"> indicates required field</span>
                    </div>
                    <div class="w-full mt-5">
                        <label for="code" class="font-bold text-gray-700">Institutional
                            Code</label>
                        <span class="text-base text-red-500 italic">*</span>
                        <input type="text" v-model="form.code" id="code" placeholder="HEI Code"
                            class="w-full rounded placeholder-gray-400 py-2 text-base border-gray-500">
                        <FormErrorMessage :message="$page.props.errors.code" theme="dark" />
                    </div>
                    <div class="w-full mt-3">
                        <label for="name" class="font-bold text-gray-700">HEI
                            Name</label>
                        <span class="text-base text-red-500 italic">*</span>
                        <input type="text" v-model="form.name" id="name" placeholder="HEI Name"
                            class="w-full rounded placeholder-gray-400 py-2 px-4 text-base border-gray-500">
                        <FormErrorMessage :message="$page.props.errors.name" theme="dark" />
                    </div>
                    <div class="italic text-sm mt-8">
                        Select programs offered by this institution
                    </div>
                    <div>
                        <input type="search" name="search" id="search" placeholder="Search programs"
                            class="w-full rounded placeholder-gray-400 py-2 px-4 text-base border-gray-500 my-2"
                            v-model="search">
                    </div>
                    <div v-if="program_list.length == 0" class="h-96 border border-gray-500 rounded lg:max-w-xl">
                        <div class="w-auto p-5 italic text-gray-500">
                            No programs found
                        </div>
                    </div>
                    <div v-else
                        class=" h-96 overflow-y-auto border border-gray-500 rounded lg:max-w-xl w-full mx-auto p-3">
                        <div v-for="(program, index) in   program_list  " :key="program.id" class="p-2">
                            <div class="mr-2 flex flex-row items-center py-1 border-b border-gray-300">
                                <input type="checkbox" :id="'programCheck' + index" class="mr-2 text-blue-500"
                                    :checked="props.programsOffered.includes(program.id)"
                                    @change="addProgram(program.id, index)" ref="programCheckbox">
                                <label class="cursor-pointer" :for="'programCheck' + index">
                                    {{ program.program }}
                                    <span v-if="program.major"> - {{ program.major }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-5 w-full text-center">
                    <button @click="submit" :disabled="processing"
                        class="mx-auto w-full bg-blue-500 rounded hover:bg-blue-600 text-white py-2 px-4">
                        <span v-if="!processing">Update</span>
                        <span v-else><i class="fas fa-spinner animate-spin"></i></span>
                    </button>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
    import { useForm, router } from '@inertiajs/vue3';
    import { ref, reactive, computed } from 'vue';

    const props = defineProps({
        'institution': Object,
        'program_list': Array,
        'programsOffered': Array,
    });

    const processing = ref(false);
    const programCheckbox = ref([]);

const refs = {
    programCheckbox,
    // selectAllCheckbox: ref(null)
};

const search = ref('');

const program_list = computed(() => {
    if (search.value === '') {
        return props.program_list;
    }

    return props.program_list.filter(program => {
        return program.program.toLowerCase().includes(search.value.toLowerCase());
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
        router.post('/admin/higher-education-institutions/update', form, {
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
    import Layout from '@/Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>