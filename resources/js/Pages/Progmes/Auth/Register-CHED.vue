<template>

    <Head title="CHED Registration" />
    <div class="w-full flex justify-center mt-10 md:mt-20 select-none">
        <div
            class="bg-white mb-10 mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:rounded max-w-xl p-10 md:pt-0 md:border-gray-400">

            <div class="text-center md:p-3 md:mb-0 mb-10 md:flex items-center justify-start">
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                <h2 class="text-lg md:text-xl font-bold w-3/4 mx-auto m-5">Commission on Higher Education Registration
                </h2>
            </div>

            <div class="mt-2">
                <div>
                    <label for="role" class="block font-bold text-gray-800 ">Role</label>
                    <select id="role" v-model="form.role" class="border border-gray-500 rounded block w-full p-2"
                        :class="{ 'text-gray-500': form.role == null }">
                        <option value="null">Select role</option>
                        <option class="text-gray-800" value="Regional Director">Regional Director</option>
                        <option class="text-gray-800" value="Chief Education Program Specialist">Chief Education
                            Program
                            Specialist</option>
                        <option class="text-gray-800" value="Supervising Education Program Specialist">Supervising
                            Education Program
                            Specialist
                        </option>
                        <option class="text-gray-800" value="Education Supervisor">Education Supervisor</option>
                    </select>
                    <form-error-message :message="$page.props.errors.role" theme="dark" />
                </div>

                <div class="mt-5" v-if="checkRole()">
                    <div>
                        <label for="discipline" class="mb-2 font-bold text-gray-800 ">Discipline(s)</label>
                    </div>
                    <select id="discipline" v-model="form.discipline"
                        class="border border-gray-500 rounded block w-full p-2"
                        :class="{ 'text-gray-500': form.discipline == null }">
                        <option :value="null">Select discipline</option>
                        <option class="text-gray-800" v-for="discipline in discipline_list" :key="discipline.id"
                            :value="discipline.id">{{
                            discipline.discipline }}</option>
                    </select>
                    <form-error-message :message="$page.props.errors.discipline" theme="dark" />
                    <form-error-message :message="$page.props.errors.other_discipline" theme="dark" />
                    <div v-for="(discipline, index) in form.other_discipline" :key="index" class="flex mt-2">
                        <div class="w-full">
                            <select v-model="form.other_discipline[index]"
                                class="border border-gray-500 rounded block w-full p-2.5"
                                :class="{ 'text-gray-500': form.other_discipline[index] == null }">
                                <option :value="null">Select discipline</option>
                                <option class="text-gray-800" v-for="discipline in discipline_list" :key="discipline.id"
                                    :value="discipline.id">{{ discipline.discipline }}</option>
                            </select>
                        </div>
                        <div @click="removeField(index)"
                            class="text-lg flex justify-center items-center w-1/12 mx-1 hover:text-red-400 text-gray-500 cursor-pointer">
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <input type="button" @click="addField"
                        class="border border-blue-600 hover:text-white hover:bg-blue-600 text-blue-600 h-8 px-3 rounded cursor-pointer mt-2"
                        value="+ Add discipline">
                </div>
                <div class="text-center mt-10">
                    <button @click="submit" :disabled="processing"
                        class=" bg-blue-600 hover:bg-blue-800 text-white p-2 px-5 max-w-16 rounded w-full cursor-pointer">
                        REGISTER
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { reactive, ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { Inertia } from '@inertiajs/inertia';


    const form = reactive({
        role: null,
        discipline: null,
        other_discipline: [],
    })

    const processing = ref(false);

    // submit
    function submit() {
        router.post('/register/ched', form, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            },
            preserveState: true,
        });
    }


    // methods
    function addField() {
        form.other_discipline.push(null);
    };

    function removeField(index) {
        form.other_discipline.splice(index, 1);
    };


    function checkRole() {
        if (form.role === 'Education Supervisor') {
            return true;
        } else {
            form.discipline = null;
            form.other_discipline = [];
            return false;
        }

    }



    const props = defineProps([
        'discipline_list',
    ]);



</script>