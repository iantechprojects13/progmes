<template>

    <Head title="Edit CMO" />
    <AdminPanel />
    <content-container :hasAction="true">
        <template v-slot:channel>
            <div class="relative" v-if="cmo.status == 'draft'" @mouseleave="openSaveOption = false">
                <button class="border border-gray-400 py-1 px-4 h-10 rounded bg-blue-500 hover:bg-blue-600 text-white"
                    @click="openSaveOption = !openSaveOption">Save
                    <i class=" fas fa-caret-down ml-2"></i> </button>
                <div v-show="openSaveOption"
                    class="absolute top-10 left-0 border border-gray-300 bg-white drop-shadow-xl w-44 rounded py-2">
                    <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7" @click.prevent="saveAsDraft">
                        Save as draft
                    </button>
                    <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
                        @click.prevent="publish">Publish</button>
                </div>
            </div>
            <div v-else>
                <button class="border border-gray-400 py-1 w-20 h-10 rounded bg-blue-500 hover:bg-blue-600 text-white">
                    Save
                </button>
            </div>
            <div class="ml-1.5">
                <Link :href="cmo.status == 'draft' ? '/admin/CMOs/draft' : route('admin.cmo.list')">
                <button class="border border-gray-400 py-1 px-4 h-10 rounded">
                    Cancel
                </button>
                </Link>
            </div>
        </template>

        <template v-slot:content-head>
            <div class="border-b border-gray-400">
                <div class="overflow-x-auto pb-5">
                    <div class="px-3 pt-3 font-bold text-gray-600 text-base">
                        CMO Details
                    </div>
                    <div class="w-full flex flex-col justify-between p-3">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full flex flex-col mt-2">
                                <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                                <select id="discipline" v-model="form.discipline"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1"
                                    :class="{ 'border-red-500 bg-red-50': this.$page.props.errors.discipline }"
                                    @change="form.program = null">
                                    <option :value="null">Select discipline</option>
                                    <option v-for="discipline in discipline_list" :key="discipline.id"
                                        :value="discipline.id">
                                        {{ discipline.discipline }}
                                    </option>
                                </select>
                                <FormErrorMessage :message="this.$page.props.errors.discipline" theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2">
                                <label for="program" class="font-bold text-gray-600">Program</label>
                                <select id="program" v-model="form.program"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1"
                                    :class="{ 'border-red-500 bg-red-50': this.$page.props.errors.program }"
                                    :disabled="!form.discipline">
                                    <option :value="null">Select program</option>
                                    <option v-for="program in program_list" :key="program.id" :value="program.id"
                                        v-show="program.disciplineId == form.discipline">
                                        {{ program.program }}
                                    </option>
                                </select>
                                <FormErrorMessage :message="this.$page.props.errors.program" theme="dark" />
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full flex flex-col mt-2">
                                <label for="number" class="font-bold text-gray-600">CMO No.</label>
                                <input id="number" type="number" placeholder="CMO No." v-model="form.number"
                                    class="h-10 m-1 rounded text-sm border border-gray-400"
                                    :class="{ 'border-red-500 bg-red-50': this.$page.props.errors.number }">
                                <FormErrorMessage :message="this.$page.props.errors.number" theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2">
                                <label for="series" class="font-bold text-gray-600">Series</label>
                                <input id="series" type="number" placeholder="Series" v-model="form.series"
                                    class="h-10 m-1 rounded text-sm border border-gray-400"
                                    :class="{ 'border-red-500 bg-red-50': this.$page.props.errors.series }">
                                <FormErrorMessage :message="this.$page.props.errors.series" theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2">
                                <label for="version" class="font-bold text-gray-600">Version</label>
                                <input id="version" type="number" placeholder="Version" v-model="form.version"
                                    class="h-10 m-1 rounded text-sm border border-gray-400"
                                    :class="{ 'border-red-500 bg-red-50': this.$page.props.errors.version }">
                                <FormErrorMessage :message="this.$page.props.errors.version" theme="dark" />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </template>

        <template v-slot:content>
            <div>
                <content-table>
                    <template v-slot:table-head>
                        <th class="px-2 py-3 border-t border-gray-400 bg-stone-200">Item No</th>
                        <th class="px-2 py-3 border-t border-gray-400 bg-stone-200">Area</th>
                        <th class="px-2 py-3 border-t border-gray-400 bg-stone-200">Minimum Requirement</th>
                    </template>

                    <template v-slot:table-body>
                        <tr v-for="(item, index) in  areaArray " :key="index"
                            :class="{ 'bg-slate-200': index % 2 == 1 }">
                            <td class="w-24 text-center align-top py-5 font-bold">
                                {{ index + 1 }}
                            </td>
                            <td class="max-w-18 w-1/4 min-w-18 py-5 px-2 align-top">
                                <textarea ref="area" :id="'area' + index" v-model="form.area[index]"
                                    class="areaInput min-h-max h-10 resize-none text-sm rounded w-full overflow-hidden border border-gray-400"
                                    @focus="handleAreaInput(index)" @input="handleAreaInput(index)"></textarea>
                            </td>
                            <td class="min-w-18 py-5 px-2 align-top">
                                <textarea ref="minreq" :id="'minReq' + index" v-model="form.minReq[index]"
                                    class="minreqInput min-h-max resize-none text-sm bg-white rounded w-full overflow-hidden border border-gray-400"
                                    @focus="handleMinreqInput(index)" @input="handleMinreqInput(index)"></textarea>
                            </td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps([
    'cmo',
    'discipline_list',
    'program_list',
]);

const areaArray = computed(() => {
    let area = [];
    for (var i = 0; i < props.cmo.criteria.length; i++) {
        area.push(props.cmo.criteria[i]['area']);
    }
    return area;
});

const minReqArray = computed(() => {
    let minreq = [];
    for (var i = 0; i < props.cmo.criteria.length; i++) {
        minreq.push(props.cmo.criteria[i]['minimumRequirement']);
    }
    return minreq;
});

const form = useForm({
    id: ref(props.cmo.id),
    discipline: ref(props.cmo.disciplineId) !== null ? ref(props.cmo.disciplineId) : null,
    program: ref(props.cmo.programId) !== null ? ref(props.cmo.programId) : null,
    number: ref(props.cmo.number),
    series: ref(props.cmo.series),
    version: ref(props.cmo.version),
    area: areaArray,
    minReq: minReqArray,
});

function publish() {
    form.post('/admin/CMOs/save-and-publish', form);
}

function saveAsDraft() {
    form.post('/admin/CMOs/save-as-draft', form);
}

</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    data() {
        return {
            openSaveOption: false,
        }
    },
    layout: Layout,
    methods: {
        handleAreaInput(index) {
            let area = this.$refs.area[index];
            area.style.height = "18px";
            area.style.height = area.scrollHeight + "px";
        },

        handleMinreqInput(index) {
            let minreq = this.$refs.minreq[index];
            minreq.style.height = "18px";
            minreq.style.height = minreq.scrollHeight + "px";
        },
    },
}
</script>
