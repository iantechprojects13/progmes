<template>

    <Head title="Evaluation Forms" />
    <AdminPanel />
    <content-container pageTitle="Compliance Tool" hasTopButton="true" hasSearch="true" hasFilters="true"
        hasResultCount="true" :data_list="institutionProgramList">
        <template v-slot:top-button>
            <div class="flex md:flex-row flex-col">
                <div class="w-full mr-1 md:mb-0 mb-1">
                    <button @click="deployToolModal = true"
                        class="px-3 w-fit rounded h-10 bg-blue-500 hover:bg-blue-600 text-white group">
                        Deploy tool
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:result-count>
            <div class="ml-5 text-base text-gray-500">( {{ count }} Result<span v-if="count > 1">s</span> )</div>
        </template>
        <template v-slot:page-description>
            <div class="ml-3">( A.Y. {{ effectivity }} )</div>
        </template>
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-full bg-slate-100 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11 mr-2" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-3">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:options>
            <div class="mr-1">
                <Link href="/admin/tool">
                <button
                    class="px-2 border-2 w-12 whitespace-nowrap rounded h-10 text-gray-600 hover:text-black border-gray-500">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3">Institution</th>
                    <th class="p-3">Program</th>
                    <th class="p-3">CMO</th>
                    <th class="p-3">Status</th>
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institutionProgramList.data.length == 0">
                        <td class="text-center py-10 italic" colspan="5">
                            No institution or program found
                        </td>
                    </tr>
                    <tr v-else v-for="(program, index) in institutionProgramList.data" :key="program.id"
                        :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3">
                            {{ program.institution?.name }}
                        </td>
                        <td class="p-3">
                            {{ program.program?.program }}
                        </td>
                        <td class="p-3">
                            <div v-if="program.evaluation_form[0]?.status == 'Deployed'">
                                CMO No.{{ program.evaluation_form[0]?.cmo?.number }}
                                Series of {{ program.evaluation_form[0]?.cmo?.series }},
                                Version {{ program.evaluation_form[0]?.cmo?.version }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div v-if="program.evaluation_form.length == 0"
                                class="bg-gray-600 text-white w-fit px-1 rounded">
                                Pending
                            </div>
                            <div v-if="
                                    program.evaluation_form[0]?.status ==
                                    'Deployed'
                                " class="bg-green-600 text-white w-fit px-1 rounded">
                                Deployed
                            </div>
                            <div v-if="
                                    program.evaluation_form[0]?.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 rounded">
                                In Progress
                            </div>
                            <div v-if="program.evaluation_form[0]?.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-1 rounded">
                                Submitted
                            </div>
                        </td>
                        <td v-show="canEdit" class="p-3 text-right">
                            <!-- <button v-show="program.evaluation_form[0]?.status == 'In progress'"
                                class="border-2 border-gray-500 hover:text-blue-500 bg-white rounded h-10 px-2">
                                <i class="fas fa-lock mr-1"></i>Lock
                            </button> -->
                            <!-- <button v-show="program.evaluation_form[0]?.status == 'In progress'">
                                <i class="fas fa-lock"></i>
                            </button> -->
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>

    <modal :showModal="deployToolModal" @close="closeModal" @submit="deploy" type="deploy"
        title="Deploy Compliance Tool">
        <div class="p-3">
            <div>
                <label class="font-bold text-gray-700" for="program">Program</label>
                <select id="program" class="w-full h-10 text-sm rounded border-gray-400 my-0.5"
                    v-model="deployment.program">
                    <option :value="null">Select program</option>
                    <option v-for="program in program_list" :key="program.id" :value="program">
                        {{ program.program }}
                        <span v-if="program.major != null"> - {{ program.major }}</span>
                    </option>
                </select>
                <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
            </div>
            <div class="mt-3">
                <div :class="{
                        'grayscale opacity-50 pointer-events-none':
                            deployment.program == null,
                    }">
                    <label class="font-bold text-gray-700" for="cmo">CHED Memorandum Order</label>
                    <input id="cmo" type="text" disabled class="w-full text-sm rounded border-gray-400 my-0.5" :value="
                            deployment.program?.active_cmo != null
                                ? 'CMO No.' +
                                  deployment.program.active_cmo.version +
                                  ' Series of ' +
                                  deployment.program.active_cmo.series +
                                  ' - Version ' +
                                  deployment.program.active_cmo.version
                                : 'No Active CMO'
                        " />
                </div>
                <FormErrorMessage :message="$page.props.errors.cmo" theme="dark" />
            </div>
            <div class="mt-3">
                <label class="font-bold text-gray-700" for="toolEffectivity">Effective A.Y</label>
                <input v-model="deployment.effectivity" id="toolEffectivity" type="text" disabled
                    class="w-full text-sm rounded border-gray-400 my-0.5" />
            </div>
        </div>
    </modal>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref } from "vue";

    let deployToolModal = ref(false);

    const closeModal = () => {
        deployToolModal.value = false;
    };

    const props = defineProps([
        "institutionProgramList",
        "count",
        "effectivity",
        "program_list",
        "defaultAcademicYear",
        "canEdit",
        "filters",
    ]);

    const form = useForm({
        effectivity: ref(props.effectivity),
    });

    const deployment = useForm({
        program: null,
        cmo: null,
        effectivity: ref(props.effectivity),
    });

    function deploy() {
        if (deployment.program?.active_cmo) {
            deployment.cmo = deployment.program.active_cmo;
        } else {
            deployment.cmo = null;
        }

        if (deployment.program != null && deployment.cmo != null) {
            closeModal();
        }

        deployment.post("/admin/form/deploy", deployment);
    }

    function changeAcademicYear(year) {
        router.get("/admin/tool/" + year);
    }

    const query = useForm({
        search: props.filters.search,
    });

    function submit() {
        if (query.search == "") {
            router.get("/admin/tool");
        } else {
            query.get("/admin/tool", {
                preserveScroll: true,
            });
        }
    }
</script>

<script>
    import Layout from "../Shared/Layout.vue";
    import FormErrorMessage from "../Shared/FormErrorMessage.vue";
    export default {
        data() {
            return {
                openSelectPageDropdown: false,
                academicYear: ["2023-2024", "2024-2025", "2025-2026"],
            };
        },
        layout: Layout,
        methods: {
            toggleBg() {
                this.ishidden = !this.ishidden;
            },
        },
    };
</script>