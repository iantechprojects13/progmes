<template>
    <Head title="HEI Registration" />
    <div class="w-full bg-white flex justify-center mt-20 select-none">
        <div class="mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:rounded max-w-xl px-10 pb-10">
            <!-- header -->
            <div class="text-center md:p-3 md:mb-0 mb-10 md:flex items-center justify-start">
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                <h2 class="text-xl md:text-2xl font-bold w-3/4 mx-auto m-5">Higher Education Institution Registration</h2>
            </div>
            <!-- select fields -->
            <div class="mt-5">
                <form class="mx-auto" @submit.prevent="submit" method="POST">
                    <!-- institution -->
                    <div>
                        <div>
                            <label for="institution" class="mb-2 font-medium text-gray-700 dark:text-white">Higher Education
                                Institution</label>
                        </div>
                        <select id="institution" v-model="form.institution"
                            class="border border-gray-400 text-gray-700 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Select institution</option>
                            <option v-for="institution in institution_list" :key="institution.id" :value="institution.id">{{
                                institution.name }}</option>
                        </select>
                        <!-- institution error -->
                        <div v-if="$page.props.errors.institution">
                            <span class="text-sm text-red-600">{{ $page.props.errors.institution }}</span>
                        </div>
                    </div>
                    <!-- role -->
                    <div class="mt-3">
                        <label for="role" class="block font-medium text-gray-700 dark:text-white">Role</label>
                        <select id="role" v-model="form.role" @change="checkRole()"
                            class="border border-gray-400 text-gray-700 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Select role</option>
                            <option value="Vice-President for Academic Affairs">Vice-President for Academic Affairs</option>
                            <option value="Dean">Dean</option>
                            <option value="Program Head">Program Head</option>
                        </select>
                        <!-- role error -->
                        <div v-if="$page.props.errors.role">
                            <span class="text-sm text-red-600">{{ $page.props.errors.role }}</span>
                        </div>
                    </div>
                    <!-- discipline -->
                    <div class="mt-3" v-if="role.isDean">
                        <div>
                            <label for="discipline"
                                class="mb-2 font-medium text-gray-700 dark:text-white">Discipline</label>
                        </div>
                        <select id="discipline" v-model="form.discipline"
                            class="border border-gray-400 text-gray-700 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Select discipline</option>
                            <option v-for="discipline in discipline_list" :key="discipline.id" :value="discipline.id">{{
                                discipline.discipline }}</option>
                        </select>
                        <!-- discipline error -->
                        <div v-if="$page.props.errors.discipline">
                            <span class="text-sm text-red-600">{{ $page.props.errors.discipline }}</span>
                        </div>
                    </div>
                    <!-- program -->
                    <div class="mt-3" v-if="role.isPH">
                        <div>
                            <label for="program" class="mb-2 font-medium text-gray-700 dark:text-white">Program</label>
                        </div>
                        <select id="program" v-model="form.program"
                            class="border border-gray-400 text-gray-700 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Select program</option>
                            <option v-for="program in program_list" :key="program.id" :value="program.id">{{
                                program.program }}<span v-if="program.major"> - {{ program.major }}</span></option>
                        </select>
                        <!-- program error -->
                        <div v-if="$page.props.errors.program">
                            <span class="text-sm text-red-600">{{ $page.props.errors.program }}</span>
                        </div>
                    </div>
                    <div class="text-center mt-7">
                        <input type="submit"
                            class=" bg-blue-600 hover:bg-blue-800 text-white p-2 px-5 rounded max-w-16 w-full cursor-pointer"
                            value="REGISTER">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';


const form = reactive({
    institution: '',
    role: '',
    discipline: '',
    program: '',
})

const role = reactive({
    isDean: false,
    isPH: false,
});


// submit
function submit() {
    router.post('/register/hei', form);
}


function checkRole() {
    if (form.role === 'Dean') {
        role.isDean = true;
        role.isPH = false;
        form.program = '';
    } else if (form.role === 'Program Head') {
        role.isPH = true;
        role.isDean = false;
        form.discipline = '';
    } else {
        role.isPH = false;
        role.isDean = false;
        form.discipline = '';
        form.program = '';
    }
}




const props = defineProps([
    'discipline_list',
    'program_list',
    'institution_list',
]);

</script>

