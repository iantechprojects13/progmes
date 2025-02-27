<template>

    <Head title="Edit CMO" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="w-full flex flex-row items-center">
                    <Link :href="$page.url.includes('/admin/CMOs/draft') ? '/admin/CMOs/draft' : '/admin/CMOs/'">
                    <button class="w-8 h-8 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back"><i class="fas fa-arrow-left"></i></button>
                    </Link>
                    <div class="ml-3 font-bold">Edit CMO</div>
                </div>
            </div>
            <div>
                <dropdown-option position="right">
                    <template v-slot:button>
                        <button class="w-24 h-10 bg-blue-500 hover:bg-blue-600 rounded text-white">
                            Save
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                    </template>
                    <template v-slot:options>
                        <div class="w-48">
                            <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7" ref="saveBtn"
                                @click.prevent="saveAsDraft">
                                Save as draft
                            </button>
                            <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7" @click.prevent="publish">
                                Publish
                            </button>
                        </div>
                    </template>
                </dropdown-option>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="border-gray-400">
                <div class="border border-gray-300 rounded my-5">
                    <div class="w-full flex flex-col justify-between p-5">
                        <div class="flex flex-col lg:flex-row">
                        <div class="w-full flex flex-col mt-2 lg:mt-0">
                                <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                                <select id="discipline" v-model="form.discipline"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1"
                                    @change="form.program = null">
                                    <option :value="null">
                                        Select discipline
                                    </option>
                                    <option v-for="discipline in discipline_list" :key="discipline.id"
                                        :value="discipline.id">
                                        {{ discipline.discipline }}
                                    </option>
                                </select>
                                <FormErrorMessage :message="
                                        $page.props.errors.discipline
                                    " theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2 lg:mt-0">
                                <label for="program" class="font-bold text-gray-600">Program</label>
                                <select id="program" v-model="form.program"
                                    class="py-1 px-3 h-10 text-sm border border-gray-400 rounded m-1"
                                    :disabled="!form.discipline">
                                    <option :value="null">
                                        Select program
                                    </option>
                                    <option v-for="program in program_list" :key="program.id" :value="program.id"
                                        v-show="
                                            program.disciplineId ==
                                            form.discipline
                                        ">
                                        {{ program.program }}
                                        <span v-if="program.major != null"> - {{ program.major }}</span>
                                    </option>
                                </select>
                                <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row md:mt-3">
                            <div class="w-full flex flex-col mt-2 lg:mt-0">
                                <label for="number" class="font-bold text-gray-600">CMO No.</label>
                                <input id="number" type="number" placeholder="CMO No." v-model="form.number"
                                    class="h-10 m-1 rounded text-sm border border-gray-400" />
                                <FormErrorMessage :message="$page.props.errors.number" theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2 lg:mt-0">
                                <label for="series" class="font-bold text-gray-600">Series</label>
                                <input id="series" type="number" placeholder="Series" v-model="form.series"
                                    class="h-10 m-1 rounded text-sm border border-gray-400" />
                                <FormErrorMessage :message="$page.props.errors.series" theme="dark" />
                            </div>
                            <div class="w-full flex flex-col mt-2 lg:mt-0">
                                <label for="version" class="font-bold text-gray-600">Version</label>
                                <input id="version" type="number" placeholder="Version" v-model="form.version"
                                    class="h-10 m-1 rounded text-sm border border-gray-400" />
                                <FormErrorMessage :message="$page.props.errors.version" theme="dark" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <content-table>
                <template v-slot:table-head>
                    <th class="px-2 py-3 border-b border-gray-400">
                        Item No
                    </th>
                    <th class="px-2 py-3 border-b border-gray-400">
                        Area
                    </th>
                    <th class="px-2 py-3 border-b border-gray-400">
                        Minimum Requirement
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-for="(item, index) in areaArray" :key="index" :class="{ 'bg-gray-100': index % 2 == 1 }" class="hover:bg-gray-200">
                        <td class="w-24 text-center align-top py-5 font-bold">
                            {{ index + 1 }}
                        </td>
                        <td class="max-w-18 w-1/4 min-w-18 py-5 px-2 align-top">
                            <textarea ref="area" :id="'area' + index" v-model="form.area[index]" maxlength="255"
                                class="areaInput min-h-max h-10 resize-none text-sm rounded w-full overflow-hidden border border-gray-400"
                                @focus="handleAreaInput(index)" @input="handleAreaInput(index)"></textarea>
                        </td>
                        <td class="min-w-18 py-5 px-2 align-top">
                            <textarea ref="minreq" :id="'minReq' + index" v-model="form.minReq[index]" maxlength="5000"
                                class="minreqInput min-h-max resize-none text-sm bg-white rounded w-full overflow-hidden border border-gray-400"
                                @focus="handleMinreqInput(index)" @input="handleMinreqInput(index)"></textarea>
                        </td>
                        <td>
                            <tip-tap-editor v-model="form.minReq[index]" /> This is the new component
                            
                        </td>
                    </tr>
                </template>
            </content-table>

        </template>
    </content-container>
    
    <Notification v-if="saving" message="Processing..." type="processing" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, computed, onUnmounted, onMounted, reactive } from "vue";

    onMounted(() => {
        window.addEventListener('keydown', handleKeyDown);
    });

    onUnmounted(() => {
        window.removeEventListener('keydown', handleKeyDown);
    });

    const handleKeyDown = (event) => {
        if (event.ctrlKey || event.metaKey) {
            if (event.key === 's' || event.key === 'S') {
                event.preventDefault();
                refs.saveBtn.value.click();
            }
        }
    };

    const props = defineProps(["cmo", "discipline_list", "program_list"]);
    const saving = ref(false);
    const addRowModal = ref(false);

    const saveBtn = ref([]);

    const refs = { saveBtn };


    const areaArray = computed(() => {
        let area = [];
        for (var i = 0; i < props.cmo.criteria.length; i++) {
            area.push(props.cmo.criteria[i]["area"]);
        }
        return area;
    });

    const minReqArray = computed(() => {
        let minreq = [];
        for (var i = 0; i < props.cmo.criteria.length; i++) {
            minreq.push(props.cmo.criteria[i]["minimumRequirement"]);
        }
        return minreq;
    });

    function addRow() {
        form.other_discipline.push(null);
    };

    function removeRow(index) {
        form.other_discipline.splice(index, 1);
    };

    const form = useForm({
        id: ref(props.cmo.id),
        discipline:
            ref(props.cmo.disciplineId) !== null
                ? ref(props.cmo.disciplineId)
                : null,
        program:
            ref(props.cmo.programId) !== null ? ref(props.cmo.programId) : null,
        number: ref(props.cmo.number),
        series: ref(props.cmo.series),
        version: ref(props.cmo.version),
        area: areaArray,
        minReq: minReqArray,
    });

    function publish() {
        router.post("/admin/CMOs/save-and-publish", form, {
            replace: true,
        });
    }

    function saveAsDraft() {
        router.post("/admin/CMOs/save-as-draft", form, {
            replace: true,
        });
    }
</script>

<script>
    import Layout from "../Shared/Layout.vue";
    export default {
        data() {
            return {
                openSaveOption: false,
            };
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
    };
</script>