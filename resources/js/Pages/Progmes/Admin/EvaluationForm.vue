<template>
    <Head title="Evaluation Forms" />
    <AdminPanel />
    <content-container placeholder="Search" :data_list="list">
        <template v-slot:channel>
            <div class="relative" @mouseleave="openSelectPageDropdown = false">
                <button class="px-3 rounded h-10 text-blue-500 border border-blue-500"
                    @click.prevent="openSelectPageDropdown = !openSelectPageDropdown"><b>A.Y {{ effectivity }}</b><i
                        class="fas fa-caret-down ml-2"></i></button>
                <div v-show="openSelectPageDropdown"
                    class="flex flex-col items-start absolute top-10 left-0 shadow border border-gray-300 bg-white rounded py-3 z-30 w-48"
                    @mouseover.prevent="openSelectPageDropdown = true">
                    <button v-for="(year, index) in academicYear" :key="index"
                        class="flex flex-row py-2 w-full px-2 text-left text-gray-500 hover:text-gray-700 hover:bg-gray-200 pl-4"
                        @click="changeAcademicYear(year)">
                        <div class="w-6">
                            <i v-show="year == effectivity" class="fas fa-check"></i>
                        </div>
                        <div>A.Y {{ year }}</div>
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:actions>
            <button @click="deployToolModal = true;"
                class="text-gray-500 hover:text-black hover:bg-blue-200 border shadow shadow-gray-500 hover:shadow-gray-700 border-gray-400 w-10 h-10 rounded tooltipForActions group"
                data-tooltip="Deploy compliant tool">
                <i class="fas fa-rocket text-base group-hover:text-lg"></i>
            </button>
        </template>
        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="scope-col p-3">Program/Institution</th>
                    <th class="scope-col p-3">CMO/Criteria</th>
                    <th class="scope-col p-3">Status</th>
                    <th class="scope-col p-3 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-for="item in  props.list.data " :key="item.id" class="border-b border-gray-300">
                        <td class="p-3 flex flex-col">
                            <span class="font-bold">{{ item.program.program }}</span>
                            <span>{{ item.institution.name }}</span>
                        </td>
                        <td class="p-3 whitespace-normal" v-if="item.evaluation">
                            <span v-if="item.evaluation.cmo">
                                CMO No.{{ item.evaluation.cmo.number }}
                            </span>
                            <span v-else>
                                -
                            </span>
                        </td>
                        <td class="p-3" v-else-if="item.program.cmo">
                            -
                        </td>
                        <td class="p-3" v-else>
                            -
                        </td>
                        <td class="p-3 whitespace-normal" v-if="item.evaluation">
                            <span v-if="item.evaluation?.item.length > 0"
                                class="p-1 text-xs bg-green-500 rounded text-white">
                                In progress
                            </span>
                            <span v-else class="p-1 text-xs bg-blue-500 rounded text-white">
                                Deployed
                            </span>
                        </td>
                        <td class="p-3" v-else>
                            <span class="p-1 text-xs bg-gray-500 rounded text-white">
                                Pending
                            </span>
                        </td>
                        <td class="p-3 text-right relative">
                            <!-- <button
                                class="text-blue-500 hover:text-blue-600 hover:bg-gray-200 border shadow shadow-gray-500 hover:shadow-gray-700 border-gray-400 w-10 h-10 rounded tooltipForActions"
                                data-tooltip="Deploy">
                                <i class="text-lg fas fa-rocket"></i>
                            </button> -->
                        </td>
                    </tr>
                    <tr v-show="list.data.length === 0">
                        <td colspan="4" class="h-24 text-center">No data</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal :showModal="deployToolModal" @close="closeModal" @submit="deploy" type="deploy" title="Deploy Compliant Tool">
        <div class="p-3">
            <div>
                <label for="program">Program</label>
                <select id="program" class="w-full h-10 text-sm rounded border-gray-400 my-0.5"
                    v-model="deployment.program">
                    <option :value="null">Select program</option>
                    <option v-for="program in program_list" :value="program">
                        {{ program.program }}
                    </option>
                </select>
                <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
            </div>
            <div class="mt-3">
                <div :class="{ 'grayscale opacity-50 pointer-events-none': deployment.program == null }">
                    <label for="cmo">Active CMO</label>
                    <input id="cmo" type="text" disabled class="w-full text-sm rounded border-gray-400 my-0.5" :value="deployment.program?.active_cmo != null ?
                        'CMO No.' + deployment.program.active_cmo.version + ' Series of ' + deployment.program.active_cmo.series + ' - Version ' + deployment.program.active_cmo.version
                        : 'No Active CMO'">
                </div>
                <FormErrorMessage :message="$page.props.errors.cmo" theme="dark" />
            </div>
            <div class="mt-3">
                <label for="toolEffectivity">Effective A.Y</label>
                <input v-model="deployment.effectivity" id="toolEffectivity" type="text" disabled
                    class="w-full text-sm rounded border-gray-400 my-0.5">
            </div>
        </div>
    </modal>
    <Notification :message="$page.props.flash.success" />
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

let deployToolModal = ref(false);

const closeModal = () => {
    deployToolModal.value = false;
};

const props = defineProps([
    'list',
    'effectivity',
    'program_list',
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

    deployment.post('/admin/form/deploy', deployment);

}


function changeAcademicYear(year) {
    form.get('/admin/tool/' + year);
}



</script>

<script>
import Layout from '../Shared/Layout.vue';
import FormErrorMessage from '../Shared/FormErrorMessage.vue';
export default {
    data() {
        return {
            openSelectPageDropdown: false,
            academicYear: [
                '2023-2024', '2024-2025', '2025-2026',
            ]
        }
    },
    layout: Layout,
    methods: {
        toggleBg() {
            this.ishidden = !this.ishidden;
        }


    },

}
</script>