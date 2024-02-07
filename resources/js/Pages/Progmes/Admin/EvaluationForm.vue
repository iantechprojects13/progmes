<template>
    <Head title="Evaluation Forms" />
    <AdminPanel />
    <content-container placeholder="Search" :data_list="list">
        <template v-slot:channel>
            <div class="mx-3">
                <label for="effectivity">Academic Year: </label>
                <select id="effectivity" class="h-8 w-32 py-0 px-2 rounded border border-gray-400 text-blue-500 text-sm"
                    v-model="form.effectivity" @change="submit">
                    <option class="text-black" value="2023-2024">2023-2024</option>
                    <option class="text-black" value="2024-2025">2024-2025</option>
                    <option class="text-black" value="2025-2026">2025-2026</option>
                    <option class="text-black" value="2026-2027">2026-2027</option>
                </select>
            </div>
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
                    <tr v-for="item in  props.list.data " :key="item" class="border-b border-gray-300">
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
                            <span v-if="item.evaluation.status === null" class="p-1 text-xs bg-red-400 rounded text-white">
                                Locked
                            </span>
                        </td>
                        <td class="p-3" v-else>
                            <span class="p-1 text-xs bg-gray-500 rounded text-white">
                                Pending
                            </span>
                        </td>
                        <td class="pr-3 text-right relative">
                            <content-table-action :item="item"
                                @create="openModal(); modalForm = item; evaluationForm.institutionProgram = item.id"
                                :canView="item.evaluation !== null" :canEdit="item.evaluation !== null"
                                :canCreate="item.evaluation == null" :canLock="item.evaluation !== null"
                                :canUnlock="item.evaluation !== null" :canArchive="item.evaluation !== null"
                                :canDelete="item.evaluation !== null"
                                :viewLink="'/admin/form/' + item.evaluation?.id + '/view'">
                            </content-table-action>
                        </td>
                    </tr>
                    <tr v-show="list.data.length === 0">
                        <td colspan="4" class="h-24 text-center">No data</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <modal v-if="showCreateModal" @close="closeModal" type="create" @create="createForm()" title="Create Evaluation Form">
        <div class="flex flex-col md:px-5">
            <div>
                <label for="program" class="font-bold">Program</label><br>
                <input id="program" :value="modalForm['program'].program" disabled
                    class="h-8 w-full text-sm bg-gray-100 text-black font-bold rounded border-gray-400" />
            </div>
            <div class="mt-3">
                <label for="institution" class="font-bold">Institution</label><br>
                <input id="institution" :value="modalForm['institution'].name" disabled
                    class="h-8 w-full text-sm bg-gray-100 text-black font-bold rounded border-gray-400" />
            </div>
            <div class="mt-3">
                <label for="effectivity" class="font-bold">Effectivity</label><br>
                <input v-model="evaluationForm.effectivity" id="effectivity" disabled
                    class="h-8 w-full text-sm bg-gray-100 text-black font-bold rounded border-gray-400" />
            </div>
            <div class="mt-3">
                <label for="cmo" class="font-bold">CMO</label><br>
                <div v-if="modalForm['program'].cmo.length > 0">
                    <select v-model="evaluationForm.cmo" id="cmo"
                        class="h-8 w-full text-sm p-1.5 rounded text-black border-gray-400">
                        <option value="">Select CMO</option>
                        <option v-for="cmo in modalForm['program'].cmo" :key="cmo.id" :value="cmo.id">
                            CMO No.{{ cmo.number }} - s.{{ cmo.series }} v.{{ cmo.version }}
                        </option>
                    </select>
                </div>
                <div v-else>
                    <input id="cmo" value="No CMO for this program" disabled
                        class="h-8 w-full text-sm bg-red-100 text-red-500 rounded border-gray-400" />
                </div>
                <div v-if="$page.props.errors.cmo">
                    <span class="text-sm text-red-600">{{ $page.props.errors.cmo }}</span>
                </div>
            </div>
        </div>
    </modal>
    <div v-if="$page.props.flash.success">
        <Notification :message="$page.props.flash.success" />
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const modalForm = {};

const props = defineProps([
    'list',
    'effectivity',
]);

const form = useForm({
    effectivity: ref(props.effectivity),
});

const evaluationForm = useForm({
    institutionProgram: null,
    cmo: "",
    effectivity: ref(props.effectivity),
});

function submit() {
    form.post('/admin/form/', form.effectivity);
}


function createForm() {
    evaluationForm.post('/admin/form/create', evaluationForm);
}


</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    data() {
        return {
            showCreateModal: false,
        }
    },
    layout: Layout,
    methods: {
        openModal() {
            this.showCreateModal = true;
        },

        closeModal() {
            this.showCreateModal = false;
        },
    },

}
</script>