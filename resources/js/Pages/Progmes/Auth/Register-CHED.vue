<template>
    <Head title="CHED Registration" />
    <div class="w-full bg-white flex justify-center mt-20">
        <div class="mx-auto md:w-4/5 md:shadow-lg md:border md:rounded-2xl max-w-xl px-10 pb-10">
            <!-- header -->
            <div class="text-center md:p-3 md:flex items-center justify-start">
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                <h2 class="text-xl md:text-2xl font-bold w-3/4 mx-auto m-5">Commission on Higher Education Registration</h2>
            </div>
            <!-- select fields -->
            <div class="mt-5">
                <form class="mx-auto" @submit.prevent="submit" method="POST">
                    <!-- role -->
                    <div>
                        <label for="role" class="block font-medium text-gray-900 dark:text-white">Role</label>
                        <select id="role" v-model="form.role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2">
                            <option value="">Select role</option>
                            <option value="Regional Director">Regional Director</option>
                            <option value="Chief Education Program Specialist">Chief Education Program Specialist</option>
                            <option value="Supervising Education Program Specialist">Supervising Education Program
                                Specialist
                            </option>
                            <option value="Education Supervisor">Education Supervisor</option>
                        </select>
                        <!-- role error -->
                        <div v-if="$page.props.errors.role">
                            <span class="text-sm text-red-600">{{ $page.props.errors.role }}</span>
                        </div>
                    </div>
                    <!-- required discipline -->
                    <div class="mt-5" v-if="checkRole()">
                        <div>
                            <label for="discipline"
                                class="mb-2 font-medium text-gray-900 dark:text-white">Discipline(s)</label>
                        </div>
                        <select id="discipline" v-model="form.discipline"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2"
                            :class="{ 'border-red-500': $page.props.errors.discipline || $page.props.errors.other_discipline }">
                            <option value="">Select discipline</option>
                            <option v-for="discipline in discipline_list" :key="discipline.id" :value="discipline.id">{{
                                discipline.discipline }}</option>
                        </select>
                        <!-- discipline error -->
                        <div v-if="$page.props.errors.discipline">
                            <span class="text-sm text-red-600">{{ $page.props.errors.discipline }}</span>
                        </div>
                        <div v-else-if="$page.props.errors.other_discipline">
                            <span class="text-sm text-red-600">{{ $page.props.errors.other_discipline }}</span>
                        </div>
                        <div v-for="(discipline, index) in form.other_discipline" :key="index" class="flex mt-2">
                            <div class="w-full">
                                <select v-model="form.other_discipline[index]"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    :class="{ 'border-red-500': $page.props.errors.other_discipline }">
                                    <option value="">Select discipline</option>
                                    <option v-for="discipline in discipline_list" :key="discipline.id"
                                        :value="discipline.id">{{ discipline.discipline }}</option>
                                </select>
                            </div>
                            <div @click="removeField(index)"
                                class="text-lg flex justify-center items-center w-1/12 mx-1 hover:text-red-400 text-gray-500 cursor-pointer">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                        <input type="button" @click="addField" class="text-sm cursor-pointer text-blue-400 mt-2"
                            value="+ Add discipline">
                    </div>
                    <div class="text-right mt-7">
                        <input type="submit"
                            class=" bg-blue-600 hover:bg-blue-800 text-white p-2 px-5 rounded-lg w-full cursor-pointer"
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
    role: '',
    discipline: '',
    other_discipline: [],
})

// submit
function submit() {
    router.post('/register/ched', form);
}


// methods
function addField() {
    form.other_discipline.push('');
};

function removeField(index) {
    form.other_discipline.splice(index, 1);
};


function checkRole() {
    if (form.role === 'Education Supervisor') {
        return true;
    } else {
        form.discipline = '';
        form.other_discipline = [];
        return false;
    }

}



const props = defineProps([
    'discipline_list',
]);



</script>


