<template>

    <Head title="New HEI" />
    <AdminPanel />

    <content-container pageTitle="Create HEI" hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/admin/higher-education-institutions">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:main-content>
            <div>
                <div class="mx-auto shadow shadow-gray-500 border border-gray-500 rounded p-5 max-w-xl">
                    <div class="flex flex-col justify-center lg:py-3">
                        <!-- <div class="lg:max-w-lg w-full mx-auto my-1.5">
                            <label for="code" class="font-bold text-gray-500">Institutional
                                Code</label>
                            <span class="text-sm ml-1 text-red-500">(Required)</span>
                            <input type="text" v-model="form.code" id="code" placeholder="HEI Code"
                                class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                :class="{ 'border-red-600': $page.props.errors.code }">
                            <div v-if="$page.props.errors.code">
                                <span class="text-sm text-red-600">{{ $page.props.errors.code }}</span>
                            </div>
                        </div> -->
                        <div class="lg:max-w-xl w-full mx-auto my-1.5">
                            <label for="name" class="font-bold text-gray-500">Institution
                                Name</label><span class="text-sm ml-1 text-red-500">(Required)</span>
                            <input type="text" v-model="form.name" id="name" placeholder="HEI Name"
                                class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                :class="{ 'border-red-600': $page.props.errors.name }">
                            <div v-if="$page.props.errors.name">
                                <span class="text-sm text-red-600">{{ $page.props.errors.name }}</span>
                            </div>
                        </div>
                        <!-- <div class="lg:max-w-lg w-full mx-auto my-1.5">
                            <label for="address" class="font-bold text-gray-500">Address</label><span
                                class="text-sm ml-1 text-red-500">(Required)</span>
                            <input type="text" v-model="form.address" id="address" placeholder="Address"
                                class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                :class="{ 'border-red-600': $page.props.errors.address }">
                            <FormErrorMessage :message="$page.props.errors.address" theme="dark" />
                        </div>
                        <div class="lg:max-w-lg w-full mx-auto my-1.5">
                            <label for="cityOrMunicipality"
                                class="font-bold text-gray-500">City/Municipality</label><span
                                class="text-sm ml-1 text-red-500">(Required)</span>
                            <select v-model="form.cityOrMunicipality" id="cityOrMunicipality"
                                class="w-full rounded py-2 px-4 text-sm text-gray-500 border-gray-300"
                                :class="{ 'border-red-600': $page.props.errors.cityOrMunicipality }">
                                <option value="">Select city or municipality</option>
                                <option v-for="  item   in   city_municipality  " :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                            <div v-if="$page.props.errors.cityOrMunicipality">
                                <span class="text-sm text-red-600">{{ $page.props.errors.cityOrMunicipality
                                    }}</span>
                            </div>
                        </div>
                        <div class="lg:max-w-lg w-full mx-auto my-1.5">
                            <label for="cityOrProvince" class="font-bold text-gray-500">Province</label><span
                                class="text-sm ml-1 text-red-500">(Required)</span>
                            <select v-model="form.cityOrProvince" id="cityOrProvince"
                                class="w-full rounded py-2 px-4 text-sm text-gray-500 border-gray-300"
                                :class="{ 'border-red-600': $page.props.errors.cityOrProvince }">
                                <option value="">Select province</option>
                                <option v-for="  item   in   province  " :key="item" :value="item">
                                    {{ item }}
                                </option>
                            </select>
                            <div v-if="$page.props.errors.cityOrProvince">
                                <span class="text-sm text-red-600">{{ $page.props.errors.cityOrProvince}}</span>
                            </div>
                        </div>
                        <div class="lg:max-w-lg w-full mx-auto my-1.5">
                            <label for="zip" class="font-bold text-gray-500 placeholder-gray-400">Postal/Zip
                                Code</label><span class="text-sm ml-1 text-red-500">(Required)</span>
                            <input type="number" v-model="form.zipCode" id="zip"
                                class="w-full rounded py-2 px-4 text-sm border-gray-300" placeholder="xxxx"
                                :class="{ 'border-red-600': $page.props.errors.zipCode }">
                            <div v-if="$page.props.errors.zipCode">
                                <span class="text-sm text-red-600">{{ $page.props.errors.zipCode }}</span>
                            </div>
                        </div> -->
                        <div class="font-bold lg:mx-3 mt-5 mb-2 text-gray-500 text-base">Programs</div>
                        <div
                            class="h-auto max-h-screen overflow-y-auto border border-gray-300 rounded lg:max-w-xl w-full mx-auto p-3">
                            <div v-for="(  program, index  ) in   program_list  " :key="program.id" class="p-2">
                                <div class="mr-2 flex flex-row items-center">
                                    <input type="checkbox" :id="'programCheck' + index" class="mr-2 text-blue-500"
                                        @change="addProgram(program.id)">
                                    <label class="cursor-pointer text-sm" :for="'programCheck' + index">
                                        {{ program.program }}
                                        <span v-if="program.major"> - {{ program.major }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:mt-2 mt-5 lg:mx-5 text-right">
                        <button @click="submit"
                            class="bg-blue-500 rounded hover:bg-blue-600 text-white py-2 px-4 mx-0.5">
                            Register
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>

    import { ref, reactive } from 'vue';
    import { router } from '@inertiajs/vue3';

    const programChecked = [];

    const form = reactive({
        code: null,
        name: null,
        address: null,
        cityOrMunicipality: '',
        cityOrProvince: '',
        zipCode: null,
        programs: ref(programChecked),
    });



    function submit() {
        router.post('/admin/higher-education-institutions/register', form);
    }


    function addProgram(id) {
        let index = this.programChecked.indexOf(id);

        if (index === -1) {
            this.programChecked.push(id);
        } else {
            this.programChecked.splice(index, 1);
        }

        console.log(this.programChecked);

    }

    defineProps([
        'program_list',
    ]);


</script>

<script>
    import Layout from '../Shared/Layout.vue';
    import FormErrorMessage from '../Shared/FormErrorMessage.vue';
    export default {
        layout: Layout,
        data() {
            return {
                city_municipality: [
                    'Davao City', 'Digos City', 'Panabo City', 'Tagum City', 'Mati City', 'Island Garden City of Samal',

                ],
                province: [
                    'Davao City', 'Davao del Sur', 'Davao Oriental',
                ],
            }
        },

    }
</script>