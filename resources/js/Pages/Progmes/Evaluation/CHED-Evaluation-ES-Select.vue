<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Evaluation" />
    <div class="md:mx-8 mx-3 mt-8">
        <div class="flex flex-col w-full rounded bg-white p-5">
            <!-- <div class="w-auto flex flex-col md:flex-row">
                <div class="font-bold lg:w-32 w-full">
                    Role
                </div>
                <div>
                    {{ role }}
                </div>
            </div> -->
            <div class="w-auto flex flex-col md:flex-row mt-3">
                <div class="font-bold w-32">
                    Discipline(s)
                </div>
                <ul>
                    <li v-for="discipline in disciplines" :key="discipline.id">
                        {{ discipline.discipline.discipline }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <content-container hasSearch="true">
        <template v-slot:search>
            <div class="w-full flex justify-end relative">
                <input @keydown.enter="submit" v-model="query.search" type="search" id="content-search"
                    placeholder="Search"
                    class="w-full rounded-full bg-slate-200 h-10 border-none indent-3 text-base placeholder-gray-400 pr-11" />
                <button @click="submit"
                    class="hover:bg-gray-300 active:text-blue-500 h-10 w-10 rounded-full absolute right-1">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </template>
        <template v-slot:main-content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-400">Program/Institution</th>
                    <th class="p-3 border-b border-gray-400">Status</th>
                    <th class="p-3 border-b border-gray-400">Progress</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-if="complianceTools.data.length == 0">
                        <td colspan="3" class="p-3 py-10 text-center">No evaluation tool found</td>
                    </tr>
                    <tr v-else v-for="(item, index) in complianceTools.data" :key="item.id"
                        class="border-b border-gray-400" :class="{'bg-slate-200': index % 2 ==0}">
                        <td class="p-3">
                            <div>
                                {{ item.program }}
                            </div>
                            <div class="font-bold">
                                {{ item.institution }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="px-1 bg-blue-500 text-white rounded w-fit">
                                {{ item.status }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div class="px-1 bg-gray-400 font-bold text-sm rounded w-fit">
                                {{ item.progress }}%
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button @click="edit(item.id)" v-show="item.status == 'Submitted'"
                                class="w-auto text-sm select-none px-2 h-10 bg-white hover:text-blue-500 border-2 border-gray-500 active:text-white active:bg-blue-600 rounded mr-1">
                                <i class="fas fa-edit mr-1"></i>Evaluate
                            </button>
                            <button
                                class="w-auto text-sm select-none px-2 h-10 bg-white hover:text-blue-500 border-2 border-gray-500 active:text-white active:bg-blue-600 rounded">
                                <i class="fas fa-eye mr-1"></i>View
                            </button>
                        </td>
                    </tr>
                </template>
            </content-table>
            <Notification :message="$page.props.flash.success" type="success" />
            <Notification v-show="processing" message="Downloading report" type="processing" />
        </template>
    </content-container>
</template>

<script setup>
    import { router, useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';

    const props = defineProps([
        'disciplines',
        'role',
        'complianceTools',
        'count',
        'filters',
    ]);

    const processing = ref(false);

    const query = useForm({
        search: props.filters.search,
        sort: props.filters.sort,
        type: props.filters.type,
    });

    function edit(tool) {
        router.get('/ched/evaluation/' + tool + '/evaluate');
    }

    function submit() {
        if (query.search == "") {
            router.get("/ched/evaluation");
        } else {
            query.get("/ched/evaluation", {
                preserveState: true,
                preserveScroll: true,
            });
        }
    }

    function downloadReport(tool) {
        router.get('/report/deficiency/' + tool + '/download')
    }

</script>

<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>

<style scoped>
    .progress-bar {
        height: 20px;
        border-radius: 50%;
    }
</style>