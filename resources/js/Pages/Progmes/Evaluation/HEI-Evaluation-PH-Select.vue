<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8 bg-white p-5 rounded">
        <div class="flex flex-col w-full rounded">
            <div class="w-auto flex flex-col md:flex-row">
                <div class="mr-2 font-bold">
                    HEI Name:
                </div>
                <div>
                    {{ program.institution?.name }}
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-2 md:mt-1">
                <div class="w-auto flex flex-col md:flex-row">
                    <div class="mr-2 font-bold">
                        Program:
                    </div>
                    <div>
                        {{ program.program.program }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <content-container>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-400">Academic Year</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="program.evaluation_form.length == 0">
                        <td colspan="3" class="text-center py-10">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in program.evaluation_form" :key="item.id"
                        class="hover:bg-gray-100 border-b border-gray-300" :class="{'bg-slate-200': index % 2 == 0}">
                        <td class="p-3 align-middle">
                            <i class="fas fa-file text-lg mr-2"></i>{{ item.effectivity }}
                        </td>
                        <td class="p-3 align-middle">
                            <div class="text-white text-sm">
                                <span class="p-1 bg-green-600 rounded" v-show="item.status === 'Deployed'">
                                    Deployed
                                </span>
                                <span class="p-1 bg-blue-500 rounded" v-show="item.status === 'In progress'">
                                    In progress
                                </span>
                                <span class="p-1 bg-red-500 rounded" v-show="item.status === 'Locked'">
                                    Locked
                                </span>
                                <span class="p-1 bg-blue-500 rounded" v-show="item.status === 'Submitted'">
                                    Submitted
                                </span>
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button :disabled="starting"
                                class="rounded h-10 px-2 w-24 text-white bg-blue-500 hover:bg-blue-600"
                                @click="createTool(item.id)" v-show="item.status === 'Deployed'">
                                <span v-if="starting"><i class="fas fa-spinner animate-spin"></i></span>
                                <span v-else>Start now</span>
                            </button>
                            <button v-show="item.status === 'In progress'" @click="edit(item.id)" ref="editButton"
                                class="rounded h-10 px-2 mr-1 text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500 bg-stone-100">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>
                            <button @click="view(item.id)"
                                class="rounded h-10 px-2 text-gray-700 hover:text-black active:text-blue-500 border-2 border-gray-500 bg-stone-100"
                                v-show="item.status != 'Deployed'">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
    <Notification :message="$page.props.flash.failed" type="failed" />
    <Notification :message="$page.props.flash.success" type="success" />
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { ref } from 'vue';

    defineProps([
        'program',
    ]);

    const starting = ref(false);

    function edit(tool) {
        router.get('/hei/evaluation/' + tool + '/edit');
    }

    function view(tool) {
        router.get('/hei/evaluation/' + tool + '/view');
    }

    function createTool(item) {
        router.post('/hei/tool/create', { id: item }, {
            onStart: () => {
                starting.value = true;
            },
            onFinish: () => {
                starting.value = false;
            },
            preserveState: false,
        });
    }
</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>