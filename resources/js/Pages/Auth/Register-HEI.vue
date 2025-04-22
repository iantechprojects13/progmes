<template>

    <Head title="HEI Registration" />
    <div class="w-full flex justify-center mt-20 select-none">
        <div
            class="bg-white mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:border-gray-500 md:rounded max-w-xl p-10 md:pt-0">
            <!-- header -->
            <div class="text-center md:p-3 md:mb-0 mb-10 md:flex items-center justify-start">
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                <h2 class="text-lg md:text-xl font-bold w-3/4 mx-auto m-5">Higher Education Institution Registration
                </h2>
            </div>
            <!-- select fields -->
            <div class="mt-5">
                <form class="mx-auto" @submit.prevent="submit" method="POST">
                    <!-- institution -->
                    <div>
                        <label for="institution" class="mb-2 font-bold text-gray-800">Higher Education
                            Institution</label>
                        <select id="institution" v-model="institution"
                            class="border border-gray-500 rounded block w-full p-2"
                            :class="{ 'text-gray-500': form.institution == null }">
                            <option :value="null">Select institution</option>
                            <option class="text-gray-800" v-for="institution in sortedInstitutionList" :key="institution.id"
                                :value="institution.id">{{ institution.name }}</option>
                        </select>
                        <form-error-message :message="$page.props.errors.institution" theme="dark" />
                    </div>
                    <!-- role -->
                    <div class="mt-4" v-show="form.institution">
                        <label for="role" class="block font-bold text-gray-800">Role</label>
                        <select id="role" v-model="form.role" @change="checkRole()"
                            class="border border-gray-500 rounded block w-full p-2"
                            :class="{ 'text-gray-500': form.role == '' }">
                            <option value="">Select role</option>
                            <option class="text-gray-800" value="Vice-President for Academic Affairs">VPAA/Dean of Multiple Discipline/Program</option>
                            <option class="text-gray-800" value="Dean">Dean of Specific Discipline/Program</option>
                            <option class="text-gray-800" value="QA Head">QA Head</option>
                            <option class="text-gray-800" value="Program Head">Program Head</option>
                            <option class="text-gray-800" value="Program Coordinator">Program Coordinator</option>
                            <option class="text-gray-800" value="Librarian">Librarian</option>
                        </select>
                        <form-error-message :message="$page.props.errors.role" theme="dark" />
                    </div>
                    <!-- discipline -->
                    <div class="mt-4" v-if="role.isDean">
                        <label for="discipline" class="mb-2 font-bold text-gray-800">Discipline</label>
                        <select id="discipline" v-model="form.discipline"
                            class="border border-gray-500 rounded block w-full p-2"
                            :class="{ 'text-gray-500': form.discipline == null }">
                            <option :value="null">Select discipline</option>
                            <option class="text-gray-800" v-for="discipline in sortedDisciplineList" :key="discipline.id"
                                :value="discipline.id">{{ discipline.discipline }}</option>
                        </select>
                        <form-error-message :message="$page.props.errors.discipline" theme="dark" />
                    </div>
                    <!-- program -->
                    <div class="mt-4" v-if="role.isPH">
                        <div>
                            <label for="program" class="mb-2 font-bold text-gray-800">Program</label>
                        </div>
                        <select id="program" v-model="form.program"
                            class="border border-gray-500 rounded block w-full p-2"
                            :class="{ 'text-gray-500': form.program == null }">
                            <option :value="null">Select program</option>
                            <option class="text-gray-800" v-for="program in sortedProgramList" :key="program.id"
                                :value="program.program.id">
                                {{ program.program?.program }}
                                <span v-if="program.program?.major"> - {{ program.program?.major
                                    }}</span>
                            </option>
                        </select>
                        <form-error-message :message="$page.props.errors.program" theme="dark" />
                    </div>
                    <div class="text-center mt-10">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-800 text-white p-2 px-5 rounded-full md:w-64 w-full"
                        :class="{ processing: 'cursor-not-allowed' }"
                        :disabled="processing"
                        >
                        <span v-if="processing">
                            <i class="fas fa-spinner animate-spin mr-1"></i>
                        </span>
                        REGISTER
                    </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, ref, watch, computed } from 'vue';
    import { router } from '@inertiajs/vue3';

    const props = defineProps([
        'discipline_list',
        'program_list',
        'institution_list',
        'institution',
        'discipline',
        'program',
    ]);

    
    const institution = ref(props.institution);
    const processing = ref(false);

    watch(institution, value => {
        router.post('/register/higher-education-institution', { institution: value }, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            },
            preserveScroll: true,
            preserveState: false,
            replace: true,
        });
    });

    const form = reactive({
        institution: ref(props.institution),
        role: '',
        discipline: null,
        program: null,
    })

    const role = reactive({
        isDean: false,
        isPH: false,
    });

    const sortedProgramList = computed(() => {
        return [...props.program_list].sort((a, b) => {
            if (a.program.program < b.program.program) return -1;
            if (a.program.program > b.program.program) return 1;
            return 0;
        });
    });
    
    const sortedInstitutionList = computed(() => {
        return [...props.institution_list].sort((a, b) => {
            if (a.name < b.name) return -1;
            if (a.name > b.name) return 1;
            return 0;
        });
    });

    const sortedDisciplineList = computed(() => {
        return [...props.discipline_list].sort((a, b) => {
            if (a.discipline < b.discipline) return -1;
            if (a.discipline > b.discipline) return 1;
            return 0;
        });
    });



    function submit() {
        router.post('/register/hei', form, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            },
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });
    }


    function checkRole() {
        if (form.role === 'Dean') {
            role.isDean = true;
            role.isPH = false;
            form.program = null;
        } else if (form.role === 'Program Head' || form.role === 'Program Coordinator') {
            role.isPH = true;
            role.isDean = false;
            form.discipline = null;
        } else {
            role.isPH = false;
            role.isDean = false;
            form.discipline = null;
            form.program = null;
        }
    }

</script>