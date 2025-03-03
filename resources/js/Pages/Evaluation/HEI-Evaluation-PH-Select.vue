<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-16 mx-3 mt-8 bg-white p-3 px-5 rounded border border-gray-300">
        <div>
            <i class="fas fa-book mr-2 text-blue-500"></i>
            <span v-if="program?.program?.program">{{ program?.program?.program }}</span>
            <span v-if="program?.program?.major">{{ program?.program?.major }}</span>
        </div>
        <div><i class="fas fa-institution mr-3 text-blue-500"></i>{{ program?.institution?.name }}</div>
    </div>
    <content-container :hideTopBorder="true">
        <template v-slot:main-content>
            <div class="py-3"></div>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-2 pl-5">Academic Year</th>
                    <th class="p-2">Status</th>
                    <th class="p-2 pr-5 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="program?.evaluation_form?.length == 0">
                        <td colspan="3" class="text-center py-10">No compliance evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in program?.evaluation_form" :key="item.id"
                        class="hover:bg-gray-200 border-b" :class="{'bg-gray-100': index % 2 == 1}">
                        <td class="p-3 align-middle">
                            <i class="fas fa-file text-lg mr-2"></i>{{ item.effectivity }}
                        </td>
                        <td class="p-3 align-middle whitespace-nowrap">
                            <div class="text-white text-xs">
                                <span class="px-1 py-0.5 bg-gray-700 rounded" v-show="item.status === 'Deployed'">
                                    Deployed
                                </span>
                                <span class="px-1 py-0.5 bg-blue-500 rounded" v-show="item.status === 'In progress'">
                                    In progress
                                </span>
                                <span class="px-1 py-0.5 bg-red-500 rounded" v-show="item.status === 'Locked'">
                                    Locked
                                </span>
                                <span class="px-1 py-0.5 bg-blue-500 rounded" v-show="item.status === 'Submitted'">
                                    Ready for visit
                                </span>
                                <span class="px-1 py-0.5 bg-blue-500 rounded" v-show="item.status === 'For re-evaluation'">
                                    For re-evaluation
                                </span>
                                <span class="px-1 py-0.5 bg-green-500 rounded" v-show="item.status === 'Monitored'">
                                    Monitored
                                </span>
                            </div>
                        </td>
                        <td class="p-3 pr-5 text-right whitespace-nowrap">
                            <button :disabled="starting"
                                class="rounded h-10 px-2 w-24 text-white bg-blue-500 hover:bg-blue-600"
                                @click="createTool(item.id)" v-show="item.status === 'Deployed'">
                                <span v-if="starting"><i class="fas fa-spinner animate-spin"></i></span>
                                <span v-else>Start now</span>
                            </button>
                            <button v-show="item.status === 'In progress'" @click="edit(item.id)" ref="editButton"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-blue-500 hover:text-blue-600 tooltipForActions"
                                data-tooltip="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button @click="view(item.id)"
                                class="select-none h-8 w-8 text-xl text-center rounded-full hover:bg-gray-300 text-green-500 hover:text-green-600 tooltipForActions"
                                data-tooltip="View" v-show="item.status != 'Deployed'">
                                <i class="fas fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
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
    import Layout from '@/Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>