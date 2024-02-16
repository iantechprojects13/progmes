<template>
    <Head title="HEI List" />
    <AdminPanel />
    <content-container>
        <template v-slot:channel>
            <div>
                <button @click="allFunction" :disabled="buttonDisabled"
                    class="border border-gray-400 py-1 w-20 h-10 rounded bg-blue-500 hover:bg-blue-600 text-white"
                    :class="{ 'bg-green-500': buttonDisabled }">
                    <span v-show="!buttonDisabled">Register</span>
                    <i v-show="buttonDisabled" class="fas fa-spinner animate-spin"></i>
                </button>
            </div>
            <div class="ml-1.5">
                <Link :href="route('admin.hei.list')">
                <button class="border border-gray-400 py-1 px-4 h-10 rounded">
                    Cancel
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:content>
            <div class="p-4 w-full">
                <div class="flex flex-col border border-gray-400 h-auto text-sm">
                    <div class="lg:w-1/2 lg:p-3 p-5 mx-auto border">
                        <div class="p-3 text-base font-bold text-gray-600">
                            HEI Information
                        </div>
                        <div class="flex flex-col justify-center">
                            <div class="max-w-md w-full mx-auto">
                                <label for="code" class="font-bold text-gray-500">Institutional
                                    Code</label><br>
                                <input type="text" v-model="form.code" id="code" placeholder="HEI Code"
                                    class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                    :class="{ 'border-red-600': $page.props.errors.code }">
                                <div v-if="$page.props.errors.code">
                                    <span class="text-sm text-red-600">{{ $page.props.errors.code }}</span>
                                </div>
                            </div>
                            <div class="max-w-md w-full mx-auto">
                                <label for="name" class="font-bold text-gray-500">Institution
                                    Name</label><br>
                                <input type="text" v-model="form.name" id="name" placeholder="HEI Name"
                                    class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                    :class="{ 'border-red-600': $page.props.errors.name }">
                                <div v-if="$page.props.errors.name">
                                    <span class="text-sm text-red-600">{{ $page.props.errors.name }}</span>
                                </div>
                            </div>
                            <div class="max-w-md w-full mx-auto">
                                <label for="address" class="font-bold text-gray-500">Address</label><br>
                                <input type="text" v-model="form.address" id="address" placeholder="Address"
                                    class="w-full rounded placeholder-gray-400 py-2 px-4 text-sm border-gray-300"
                                    :class="{ 'border-red-600': $page.props.errors.address }">
                                <div v-if="$page.props.errors.address">
                                    <span class="text-sm text-red-600">{{ $page.props.errors.address }}</span>
                                </div>
                            </div>
                            <div class="max-w-md w-full mx-auto">
                                <label for="cityOrMunicipality"
                                    class="font-bold text-gray-600">City/Municipality</label><br>
                                <select v-model="form.cityOrMunicipality" id="cityOrMunicipality"
                                    class="w-full rounded py-2 px-4 text-sm text-gray-600 border-gray-300"
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
                            <div class="max-w-md w-full mx-auto">
                                <label for="cityOrProvince" class="font-bold text-gray-600">Province</label><br>
                                <select v-model="form.cityOrProvince" id="cityOrProvince"
                                    class="w-full rounded py-2 px-4 text-sm text-gray-600 border-gray-300"
                                    :class="{ 'border-red-600': $page.props.errors.cityOrProvince }">
                                    <option value="">Select province</option>
                                    <option v-for="  item   in   province  " :key="item" :value="item">
                                        {{ item }}
                                    </option>
                                </select>
                                <div v-if="$page.props.errors.cityOrProvince">
                                    <span class="text-sm text-red-600">{{ $page.props.errors.cityOrProvince
                                    }}</span>
                                </div>
                            </div>
                            <div class="max-w-md w-full mx-auto">
                                <label for="zip" class="font-bold text-gray-600 placeholder-gray-400">Postal/Zip
                                    Code</label><br>
                                <input type="number" v-model="form.zip" id="zip"
                                    class="w-full rounded py-2 px-4 text-sm border-gray-300" placeholder="xxxx"
                                    :class="{ 'border-red-600': $page.props.errors.zip }">
                                <div v-if="$page.props.errors.zip">
                                    <span class="text-sm text-red-600">{{ $page.props.errors.zip }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lg:w-1/2 p-5 pr-2 pb-2">
                        <div class="font-bold mb-3 text-gray-600 text-base">Programs</div>
                        <div class="h-96 overflow-y-scroll">
                            <div v-for="(  program, index  ) in   program_list  " :key="program.id" class="p-2">
                                <div class="mr-2 flex flex-row items-center">
                                    <input type="checkbox" :id="'programCheck' + index" class="mr-2 text-gray-500"
                                        @change="addProgram(program.id)">
                                    <label class="cursor-pointer" :for="'programCheck' + index">
                                        {{ program.program }}
                                        <span v-if="program.major"> - {{ program.major }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>

import { reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';

let buttonDisabled = false;

const programChecked = [];

const form = reactive({
    code: null,
    name: null,
    address: null,
    cityOrMunicipality: '',
    cityOrProvince: '',
    zip: null,
    programs: ref(programChecked),
});



function submit() {
    router.post('/admin/higher-education-institutions/register', form);
}


function disableButton() {
    setTimeout(() => {
        buttonDisabled = false;
    }, 1000);
}

function allFunction() {
    submit();
    disableButton();
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
