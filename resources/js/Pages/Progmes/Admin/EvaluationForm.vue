<template>

    <Head title="Evaluation Forms" />
    <AdminPanel />
    <content-container pageTitle="Compliance Evaluation Tool" :hasTopButton="canEdit" hasSearch="true" hasFilters="true"
        :hasNavigation="true" :data_list="institutionProgramList">
        <template v-slot:navigation>
            <div class="mb-2 flex items-center">
                <div class="mr-2 font-bold">Academic Year:</div>
                <select v-model="query.ay" id="academicYearDd" @change.prevent="changeAcademicYear"
                    class="select-none text-sm rounded border-2 border-gray-500">
                    <option v-for="(item, index) in academicYearDropdown" :key="index" :value="item">{{ item }}</option>
                </select>
                <div class="absolute right-8" v-show="$page.props.auth.user.role == 'Super Admin'">
                    <button class="h-10 px-3 rounded bg-gray-500 hover:bg-gray-600 text-white">Set as default
                        AY</button>
                </div>
            </div>
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
        <template v-slot:top-button>
            <div class="flex md:flex-row flex-col text-sm">
                <div class="w-full">
                    <button @click="deployToolModal = true"
                        class="select-none whitespace-nowrap px-3 w-fit rounded h-10 bg-blue-500 hover:bg-blue-600 text-white group">
                        <i class="fas fa-rocket mr-2"></i>
                        Deploy tool
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:options>
            <div>
                <Link href="/admin/tool">
                <button
                    class="w-10 h-10 whitespace-nowrap rounded-full text-gray-700 hover:text-blue-500 active:text-white active:bg-blue-600 tooltipForActions"
                    data-tooltip="Refresh page">
                    <i class="fas fa-refresh"></i>
                </button>
                </Link>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 pl-5">Institution</th>
                    <th class="p-3">Program</th>
                    <th class="p-3">Major</th>
                    <th class="p-3">CMO</th>
                    <th class="p-3">Status</th>
                    <th v-show="canEdit" class="p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="institutionProgramList.data.length == 0">
                        <td class="text-center py-10" colspan="5">
                            No compliance evaluation tool found
                        </td>
                    </tr>
                    <tr v-else v-for="(program, index) in institutionProgramList.data" :key="program.id"
                        class="hover:bg-slate-300" :class="{ 'bg-slate-200': index % 2 == 0 }">
                        <td class="p-3 whitespace-normal pl-5">
                            {{ program.institution?.name }}
                        </td>
                        <td class="p-3 whitespace-normal">
                            {{ program.program?.program }}
                        </td>
                        <td class="p-3 whitespace-normal">
                            {{ program.program?.major }}
                        </td>
                        <td class="p-3 whitespace-normal">
                            <div>
                                CMO No.{{ program.evaluation_form[0]?.cmo?.number }}
                                Series of {{ program.evaluation_form[0]?.cmo?.series }},
                                Version {{ program.evaluation_form[0]?.cmo?.version }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div v-if="program.evaluation_form.length == 0"
                                class="bg-gray-600 text-white w-fit px-1 rounded text-xs">
                                Pending
                            </div>
                            <div v-if="
                                    program.evaluation_form[0]?.status ==
                                    'Deployed'
                                " class="bg-gray-700 text-white w-fit px-1 rounded text-xs">
                                Deployed
                            </div>
                            <div v-if="
                                    program.evaluation_form[0]?.status ==
                                    'In progress'
                                " class="bg-blue-500 text-white w-fit px-1 rounded text-xs">
                                In Progress
                            </div>
                            <div v-if="program.evaluation_form[0]?.status == 'Submitted'"
                                class="bg-emerald-500 text-white w-fit px-1 rounded text-xs">
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
        <template v-slot:show-item>
            <div class="mr-2">Items per page</div>
            <select v-model="query.show" id="showResultCount" @change="changeResultCount"
                class="select-none rounded h-8 w-16 p-1 text-sm">
                <option :value="25">25</option>
                <option :value="50">50</option>
                <option :value="75">75</option>
                <option :value="100">100</option>
                <option :value="200">200</option>
            </select>
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
            </div>
            <div class="mt-3">
                <label class="font-bold text-gray-700" for="toolEffectivity">Effective A.Y.</label>
                <select v-model="deployment.effectivity" id="toolEffectivity"
                    class="w-full text-sm rounded border-gray-400 my-0.5">
                    <option v-for="(year, index) in academicYearDropdown" :key="index" :value="year">{{ year }}</option>
                </select>
            </div>
        </div>
        <template v-slot:custom-button>
            <button @click="deploy" class="text-white bg-blue-600 hover:bg-blue-700 h-10 w-20 rounded border">
                <span v-if="deploying">
                    <i class="fas fa-spinner animate-spin"></i>
                </span>
                <span v-else>Deploy</span>
            </button>
        </template>
    </modal>
    <Notification :message="$page.props.flash.success" type="success" />
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification v-if="$page.props.errors.program" :message="$page.props.errors.program" type="failed" />
    <Notification v-else :message="$page.props.errors.cmo" type="failed" />
</template>

<script setup>
    import { useForm, router } from "@inertiajs/vue3";
    import { ref, watch, reactive } from "vue";

    let deployToolModal = ref(false);
    const deploying = ref(false);

    const closeModal = () => {
        deployToolModal.value = false;
    };

    const props = defineProps([
        "institutionProgramList",
        "effectivity",
        "program_list",
        "academicYear",
        "canEdit",
        "filters",
    ]);

    const deployment = reactive({
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

        router.post("/admin/form/deploy", deployment, {
            onStart: () => {
                deploying.value = true;
            },
            onFinish: () => {
                deploying.value = false;
            },
            preserveState: false,
        });
    }

    const query = useForm({
        ay: props.filters.academicYear,
        show: props.filters.show,
        search: props.filters.search,
    });


    function submit() {
        query.get("/admin/tool", {
            preserveScroll: true,
            preserveState: false,
        });
    }

    function changeAcademicYear() {
        query.search = '';
        query.get("/admin/tool", {
            preserveScroll: true,
            preserveState: false,
        });
    }

    function changeResultCount() {
        query.get("/admin/tool", {
            preserveScroll: false,
            preserveState: true,
        });
    }


</script>

<script>
    import Layout from "../Shared/Layout.vue";
    import FormErrorMessage from "../Shared/FormErrorMessage.vue";
    export default {
        data() {
            return {
                openSelectPageDropdown: false,
                academicYearDropdown: [
                    '2023-2024',
                    '2024-2025',
                    '2025-2026',
                    '2026-2027',
                    '2027-2028',
                    '2028-2029',
                    '2029-2030',
                ],
            };
        },
        layout: Layout,
    };
</script>