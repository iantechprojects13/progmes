<template>
    <AdminPanel />
    <content-container placeholder="Search CMO">
        <template v-slot:channel>
            <Link :href="route('admin.cmo.list')">
            <button class="text-gray-500 hover:text-gray-800 mr-5">List</button>
            </Link>
            <button class="text-blue-500 font-bold underline">Create</button>
        </template>
        <template v-slot:content>
            <div class="font-bold m-3 p-3">
                New Criteria/CMO
            </div>
            <div class="w-full auto flex items-center justify-between px-3">
                <div class="w-full flex flex-col lg:flex-row">
                    <select v-model="form.discipline" class="w-full mr-1 mb-1 rounded">
                        <option value="">Select discipline</option>
                        <option v-for="discipline in discipline_list" :key="discipline.id" :value="discipline.id">
                            {{ discipline.discipline }}
                        </option>
                    </select>
                    <select v-model="form.program" class="w-full mr-1 mb-1 rounded">
                        <option value="">Select program</option>
                        <option v-for="program in program_list" :key="program.id" :value="program.id">
                            {{ program.program }}
                        </option>
                    </select>
                    <input type="number" placeholder="CMO No." v-model="form.number"
                        class="w-full lg:w-1/3 lg:mr-1 mb-1 rounded">
                    <input type="number" placeholder="Series" v-model="form.series"
                        class="w-full lg:w-1/3 lg:mr-1 mb-1 rounded">
                    <input type="number" placeholder="Version" v-model="form.version"
                        class="w-full lg:w-1/3 lg:mr-1 mb-1 rounded">
                </div>
            </div>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col py-3">Item No</th>
                    <th class="pr-2">Area</th>
                    <th class="pr-2">Minimum Requirement</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in areaArray" :key="index" class="border-b border-gray-400">
                        <td v-if="index > 0" class="w-24 text-center">
                            {{ index }}
                        </td>
                        <td v-if="index > 0" class="max-w-18 w-1/4 min-w-18 pr-2">
                            <textarea v-model="form.area[index]"
                                class="resize-none text-sm border rounded w-full  h-32 overflow-hidden"></textarea>
                        </td>
                        <td v-if="index > 0" class="py-2 pr-2 min-w-18">
                            <textarea v-model="form.minReq[index]"
                                class="resize-none text-sm border bg-white rounded w-full h-32 overflow-hidden focus:bg-white"></textarea>
                        </td>
                    </tr>
                </template>
            </content-table>
            <div class="w-full text-right mt-3">
                <button @click="submit"
                    class="p-3 m-3 border w-24 border-gray-300 text-gray-100 rounded bg-blue-500 hover:bg-blue-600 hover:text-white">
                    Save
                </button>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    'file': Array,
    'discipline_list': Array,
    'program_list': Array,
});

const areaArray = computed(() => {
    let areaArr = [];
    for (var i = 0; i < props.file[0].length; i++) {
        areaArr.push(props.file[0][i][0]);
    }
    return areaArr;
});

const minReqArray = computed(() => {
    let minReqArr = [];
    for (var i = 0; i < props.file[0].length; i++) {
        minReqArr.push(props.file[0][i][1]);
    }
    return minReqArr;
});

const form = useForm({
    area: areaArray,
    minReq: minReqArray,
    discipline: '',
    program: '',
    number: null,
    series: null,
    version: null,
});

function submit() {
    form.post('/admin/CMOs/store', form);
}
</script>


<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,

}
</script>