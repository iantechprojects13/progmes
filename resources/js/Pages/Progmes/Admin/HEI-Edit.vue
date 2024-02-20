<template>
    <Head :title="institution.name" />
    <AdminPanel />
    <content-container placeholder="Search HEI">
        <template v-slot:channel>
            <Link :href="route('admin.hei.list')">
            <button class="py-1 px-4 h-10 rounded-full">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:content>
            <div class="p-3 w-full">
                <div class="font-bold text-base mb-5">
                    {{ institution.name }}
                </div>
                <div class="w-full max-w-xl lg:shadow lg:shadow-gray-500 mx-auto lg:my-5 lg:px-5 lg:py-10">
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="code"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">Institution
                            Code</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <input type="text" id="code" class="w-full rounded text-sm border-gray-400" v-model="form.code">
                            <FormErrorMessage :message="$page.props.errors.code" theme="dark" />
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="name"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">Institution
                            Name</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <input type="text" id="name" class="w-full rounded text-sm border-gray-400" v-model="form.name">
                            <FormErrorMessage :message="$page.props.errors.name" theme="dark" />
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="address"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">Address</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <input type="text" id="address" class="w-full rounded text-sm border-gray-400"
                                v-model="form.address">
                            <FormErrorMessage :message="$page.props.errors.address" theme="dark" />
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="cityOrMunicipality"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">City/Municipality</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <select id="cityOrMunicipality" class="w-full rounded text-sm border-gray-400"
                                :class="{ 'text-gray-500': form.cityOrMunicipality == null }"
                                v-model="form.cityOrMunicipality">
                                <option :value="null">Select city or municipality</option>
                                <option class="text-black" v-for="item in city_municipality" :value="item">{{ item }}
                                </option>
                            </select>
                            <FormErrorMessage :message="$page.props.errors.cityOrMunicipality" theme="dark" />
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="cityOrProvince"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">Province</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <select id="cityOrProvince" class="w-full rounded text-sm border-gray-400"
                                :class="{ 'text-gray-500': form.cityOrProvince == null }" v-model="form.cityOrProvince">
                                v-model="form.cityOrProvince">
                                <option :value="null">Select province</option>
                                <option class="text-black" v-for="item in province" :value="item">{{ item }}</option>
                            </select>
                            <FormErrorMessage :message="$page.props.errors.cityOrProvince" theme="dark" />
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row lg:align-top mb-3">
                        <label for="zipCode"
                            class="w-full max-w-10 lg:text-right lg:mt-2 lg:mb-0 mb-1 font-bold text-gray-600">Postal
                            Code</label>
                        <div class="flex flex-col w-full lg:max-w-xl lg:ml-3">
                            <input type="number" id="zipCode" class="w-full rounded text-sm border-gray-400"
                                v-model="form.zipCode">
                            <FormErrorMessage :message="$page.props.errors.zipCode" theme="dark" />
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row mb-3">
                        <label
                            class="w-full max-w-10 lg:text-right lg:mb-0 mb-1 mt-1 font-bold text-gray-600">Programs</label>
                        <div
                            class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border border-gray-400 px-3 py-2 h-auto max-h-96 overflow-y-auto">
                            <div v-for="item in institution.institution_program" :key="item.id"
                                class="flex items-center flex-row py-1">
                                {{ item.program.program }}
                            </div>
                            <div></div>
                        </div>
                    </div>
                    <div class="w-full text-right">
                        <button @click="submit" class="px-4 py-2 w-32 rounded bg-green-700 text-white">Update</button>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    'institution': Object,
    'program_list': Array,
});

const form = useForm({
    code: ref(props.institution.code),
    name: ref(props.institution.name),
    address: ref(props.institution.address),
    cityOrMunicipality: ref(props.institution.cityOrMunicipality),
    cityOrProvince: ref(props.institution.cityOrProvince),
    zipCode: ref(props.institution.zipCode),
});

function submit() {
    form.post('/admin/higher-education-institutions/update', form);
}


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

