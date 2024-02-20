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
        <template v-slot:actions>
            <Link :href="'/admin/higher-education-institutions/' + institution.id + '/edit'">
            <button class="h-10 px-3 border rounded hover:text-black border-gray-400 text-gray-500 tooltipForActions"
                data-tooltip="Edit">
                <i class="fas fa-edit"></i>
            </button>
            </Link>
        </template>
        <template v-slot:content>
            <div class="p-3 w-full">
                <div class="font-bold text-base mb-5">
                    {{ institution.name }}
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="code" class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">Institution
                        Code</label>
                    <input type="text" disabled id="code" class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400"
                        :value="institution.code">
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="name" class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">Institution
                        Name</label>
                    <input type="text" disabled id="name" class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400"
                        :value="institution.name">
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="address"
                        class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">Address</label>
                    <input type="text" disabled id="address"
                        class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400" :value="institution.address">
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="cityOrMunicipality"
                        class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">City/Municipality</label>
                    <input type="text" disabled id="cityOrMunicipality"
                        class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400"
                        :value="institution.cityOrMunicipality">
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="cityOrProvince"
                        class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">Province</label>
                    <input type="text" disabled id="cityOrProvince"
                        class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400"
                        :value="institution.cityOrProvince">
                </div>
                <div class="flex flex-col lg:flex-row lg:items-center mb-3">
                    <label for="zipCode" class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 font-bold text-gray-600">Postal
                        Code</label>
                    <input type="text" disabled id="zipCode"
                        class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border-gray-400" :value="institution.zipCode">
                </div>
                <div class="flex flex-col lg:flex-row mb-3">
                    <label class="lg:w-72 w-auto lg:text-right lg:mb-0 mb-1 mt-1 font-bold text-gray-600">Programs</label>
                    <div
                        class="w-full lg:max-w-xl lg:ml-3 rounded text-sm border border-gray-400 px-3 py-2 h-auto max-h-96 overflow-y-auto">
                        <div v-for="item in institution.institution_program" :key="item.id"
                            class="flex items-center flex-row py-1">
                            {{ item.program.program }}
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>


const props = defineProps({
    'institution': Object,
});


</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>

