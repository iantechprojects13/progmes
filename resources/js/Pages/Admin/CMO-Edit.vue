<template>

    <Head title="Edit CMO" />
    <content-container :hasAdminPanel="true" :hasTopMainContent="true">
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
                        <button class="flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Save
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                    </template>
                    <template v-slot:options>
                        <div class="w-48">
                            <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7" ref="saveBtn"
                                @click.prevent="update">
                                Save changes
                            </button>
                            <button class="py-1.5 hover:bg-gray-200 w-full text-left indent-7"
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
            <div class="bg-white rounded-xl shadow-lg p-2 md:p-8 md:m-4 mb-12">
                <div class="flex flex-col gap-6">
                    <!-- First Row: Discipline and Program -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Discipline Section -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Discipline</p>
                                <select 
                                    v-model="form.discipline"
                                    @change="form.program = null"
                                    class="mt-1 w-full p-2 h-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                >
                                    <option :value="null">Select Discipline</option>
                                    <option 
                                        v-for="discipline in discipline_list" 
                                        :key="discipline.id" 
                                        :value="discipline.id"
                                    >
                                        {{ discipline.discipline }}
                                    </option>
                                </select>
                                <FormErrorMessage :message="$page.props.errors.discipline" theme="dark" />
                            </div>
                        </div>
            
                        <!-- Program Section -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Program</p>
                                <select 
                                    v-model="form.program" 
                                    :disabled="!form.discipline"
                                    class="mt-1 w-full p-2 h-12 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 pr-10"
                                    :class="{' cursor-not-allowed':!form.discipline}"
                                >
                                    <option :value="null">Select Program</option>
                                    <option 
                                        v-for="program in program_list" 
                                        :key="program.id" 
                                        :value="program.id"
                                        v-show="program.disciplineId == form.discipline"
                                    >
                                        {{ program.program }}
                                        <span v-if="program.major != null"> - {{ program.major }}</span>
                                    </option>
                                </select>
                                <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                            </div>
                        </div>
                    </div>
            
                    <!-- Second Row: CMO No., Series, and Version -->
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- CMO No. -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">CMO No.</p>
                                <input 
                                    id="number" 
                                    type="number" 
                                    placeholder="CMO No." 
                                    v-model="form.number"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300" 
                                />
                                <FormErrorMessage :message="$page.props.errors.number" theme="dark" />
                            </div>
                        </div>
            
                        <!-- Series -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Series</p>
                                <input 
                                    id="series" 
                                    type="number" 
                                    placeholder="Series" 
                                    v-model="form.series"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300" 
                                />
                                <FormErrorMessage :message="$page.props.errors.series" theme="dark" />
                            </div>
                        </div>
            
                        <!-- Version -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <div class="w-full">
                                <p class="text-sm text-gray-500">Version</p>
                                <input 
                                    id="version" 
                                    type="number" 
                                    placeholder="Version" 
                                    v-model="form.version"
                                    class="w-full h-12 m-1 rounded text-sm border border-gray-300" 
                                />
                                <FormErrorMessage :message="$page.props.errors.version" theme="dark" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <content-table>
                
                <template v-slot:table-head>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                        Item No
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                        Area
                    </th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                        Minimum Requirement
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-for="(item, index) in areaArray" :key="index" :class="{ 'bg-gray-100': index % 2 == 1 }" class="hover:bg-gray-200">
                        <td class="w-24 text-center align-top py-5 font-bold">
                            {{ index + 1 }}
                        </td>
                        <td class="min-w-18 max-w-sm py-3 px-2 align-top">
                            <tip-tap-editor 
                                :model-value="form.area[index]"
                            />
                        </td>
                        <td class="min-w-18 max-w-xl py-3 px-2 align-top">
                            <tip-tap-editor v-model="form.minReq[index]"/>
                            
                        </td>
                    </tr>
                </template>
            </content-table>

        </template>
    </content-container>
    
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



    // Ctrl + S to save changes
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

    const form = reactive({
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

    function update() {
        router.post("/admin/CMOs/update", form, {
            replace: true,
            preserveState: true,
            preserveScroll: true,
        });
    }

    function saveAsDraft() {
        router.post("/admin/CMOs/save-as-draft", form, {
            replace: true,
        });
    }

    function publish() {
        router.post("/admin/CMOs/save-and-publish", form, {
            replace: true,
        });
    }

</script>

<script>
    import Layout from "@/Shared/Layout.vue";
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