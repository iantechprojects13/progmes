<template>
    <AdminPanel />
    <content-container placeholder="Search CMO">
        <template v-slot:channel>
            <div class="font-bold text-gray-600 p-2">
                <i class="fas fa-file-excel mr-2"></i>{{ file_name }}
            </div>
        </template>
        <template v-slot:content-head>
            <div class="overflow-x-auto pb-5">
                <div class="px-3 pt-3 font-bold text-gray-600 text-base">
                    CMO Details
                </div>
                <div class="w-full flex items-center justify-between p-3">
                    <div class="w-full flex flex-col">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full m-1 flex flex-col">
                                <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                                <select id="discipline" v-model="form.discipline"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded mt-1"
                                    @change="form.program = ''">
                                    <option value="">Select discipline</option>
                                    <option v-for="discipline in discipline_list" :key="discipline.id"
                                        :value="discipline.id">
                                        {{ discipline.discipline }}
                                    </option>
                                </select>
                            </div>
                            <div class="w-full m-1 flex flex-col">
                                <label for="program" class="font-bold text-gray-600">Program</label>
                                <select id="program" v-model="form.program"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded mt-1 "
                                    :disabled="!form.discipline">
                                    <option value="">Select program</option>
                                    <option v-for="program in program_list" :key="program.id" :value="program.id"
                                        v-show="program.disciplineId == form.discipline">
                                        {{ program.program }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full flex flex-col mt-2">
                                <label for="number" class="font-bold text-gray-600">CMO No.</label>
                                <input id="number" type="number" placeholder="CMO No." v-model="form.number"
                                    class="h-10 m-1 rounded text-sm border border-gray-400">
                                <FormErrorMessage :message="this.$page.props.errors.number" />

                            </div>
                            <div class="w-full flex flex-col mt-2">
                                <label for="series" class="font-bold text-gray-600">Series</label>
                                <input id="series" type="number" placeholder="Series" v-model="form.series"
                                    class="h-10 m-1 rounded text-sm border border-gray-400">
                            </div>
                            <div class="w-full flex flex-col mt-2">
                                <label for="version" class="font-bold text-gray-600">Version</label>
                                <input id="version" type="number" placeholder="Version" v-model="form.version"
                                    class="h-10 m-1 rounded text-sm border border-gray-400">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-row items-center mt-3">
                    <button @click="submit"
                        class="p-3 m-3 border w-24 border-gray-300 text-gray-100 rounded bg-blue-500 hover:bg-blue-600 hover:text-white">
                        Save
                    </button>
                    <div class="flex flex-row items-center h-12 px-4 rounded border border-gray-400">
                        <input ref="showItemsChk" type="checkbox" id="showItemsChkbx" class="mr-2"
                            @change="toggleShowItems">
                        <label for="showItemsChkbx">Show all items</label>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <content-table v-if="showItems">
                <template v-slot:table-head>
                    <th class="px-2 py-3 border-t border-gray-400">Item No</th>
                    <th class="px-2 py-3 border-t border-gray-400">Area</th>
                    <th class="px-2 py-3 border-t border-gray-400">Minimum Requirement</th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="(item, index) in  areaArray " :key="index" :class="{ 'bg-slate-200': index % 2 == 1 }">
                        <td v-if="index > 0" class="w-24 text-center align-top py-5 font-bold">
                            {{ index }}
                        </td>
                        <td v-if="index > 0" class="max-w-18 w-1/4 min-w-18 py-5 px-2 align-top">
                            <textarea ref="area" :id="'area' + index" v-model="form.area[index]"
                                class="areaInput min-h-max h-10 resize-none text-sm rounded w-full overflow-hidden border border-gray-400"
                                @focus="handleAreaInput(index)" @input="handleAreaInput(index)"></textarea>
                        </td>
                        <td v-if="index > 0" class="min-w-18 py-5 px-2 align-top">
                            <textarea ref="minreq" :id="'minReq' + index" v-model="form.minReq[index]"
                                class="minreqInput min-h-max resize-none text-sm bg-white rounded w-full overflow-hidden border border-gray-400"
                                @focus="handleMinreqInput(index)" @input="handleMinreqInput(index)"></textarea>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { computed, ref } from 'vue';

    const props = defineProps({
        'file': Array,
        'file_name': String,
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
        discipline: '',
        program: '',
        number: null,
        series: null,
        version: null,
        area: areaArray,
        minReq: minReqArray,
    });


    function submit() {
        form.post('/admin/CMOs/store', form);
    }

</script>


<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        data() {
            return {
                showItems: false,
            }
        },
        layout: Layout,

        methods: {
            handleAreaInput(index) {
                let area = this.$refs.area[index - 1];
                area.style.height = "18px";
                area.style.height = area.scrollHeight + "px";
            },

            handleMinreqInput(index) {
                let minreq = this.$refs.minreq[index - 1];
                minreq.style.height = "18px";
                minreq.style.height = minreq.scrollHeight + "px";
            },

            toggleShowItems() {

                // let showItem = this.$page.refs.showItemsChk;

                // if (showItem.checked) {
                //     console.log('checked');
                // } else {
                //     console.log('unchecked');
                // }

                this.showItems = !this.showItems;
            }
        },

    }
</script>