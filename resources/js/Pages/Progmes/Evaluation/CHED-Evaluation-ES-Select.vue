<template>

    <Head title="Program Self-Evaluation" />
    <page-title title="Program Self-Evaluation" />
    <div class="md:mx-8 mx-3 mt-8">
        <div class="flex flex-col w-full rounded">
            <div class="w-auto flex flex-col md:flex-row">
                <div class="font-bold lg:w-32 w-full">
                    Role
                </div>
                <div>
                    {{ role }}
                </div>
            </div>
            <div class="w-auto flex flex-col md:flex-row mt-3">
                <div class="font-bold lg:w-32 w-full">
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
    <content-container :hasAction="true">
        <template v-slot:channel>
            <dropdown-option>
                <template v-slot:button>
                    <button class="h-10 px-3 border border-gray-500 rounded">A.Y. 2023-2024
                        <i class="fas fa-caret-down ml-2"></i></button>
                </template>

                <template v-slot:options>
                    <div class="w-48">
                        <button class="w-full text-left indent-5">A.Y. 2024-2025</button>
                    </div>
                </template>
            </dropdown-option>
        </template>

        <template v-slot:content>
            <content-table>
                <template v-slot:table-head>
                    <th class="p-3 border-b border-gray-400">Program/Institution</th>
                    <th class="p-3 border-b border-gray-400">Date of Submission</th>
                    <th class="p-3 border-b border-gray-400 text-right">
                        <i class="fas fa-ellipsis-v"></i>
                    </th>
                </template>

                <template v-slot:table-body>
                    <tr v-for="(item, index) in tools" :key="item.id" class="border-b border-gray-400">
                        <td class="p-3">
                            <div>
                                {{ item.institution_program.program.program }}
                            </div>
                            <div class="font-bold">
                                {{ item.institution_program.institution.name }}
                            </div>
                        </td>
                        <td class="p-3">
                            <div>
                                {{ item.submissionDate }}
                            </div>
                        </td>
                        <td class="p-3 text-right">
                            <button @click="edit(item.id)"
                                class="w-auto text-sm select-none px-2 h-10 bg-blue-500 hover:bg-blue-600 text-white shadow shadow-gray-500 rounded">Evaluate
                            </button>
                        </td>
                    </tr>
                    <tr v-show="tools.length == 0">
                        <td colspan="3" class="p-3 text-center">Empty</td>
                    </tr>
                </template>
            </content-table>
        </template>
    </content-container>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

defineProps([
    'disciplines',
    'role',
    'tools',
]);

function edit(tool) {
    router.get('/ched/evaluation/' + tool);
}

function createTool(item) {
    router.get('/hei/tool/create/' + item);
}
</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>