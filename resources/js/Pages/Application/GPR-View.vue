<template>
    <Head title="GPR" />
    <page-title title="Program Application" />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">GPR</div>
            </div>
            <div>
                <button
                        @click="edit"
                        class="w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Edit
                    </button>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="p-3">
            <div class="p-3 border rounded-xl shadow-lg">
                <content-table>
                    <template v-slot:table-head>
                        <th>Documents</th>
                        <th></th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="items.length == 0">
                            <no-search-result text="items" />
                        </tr>
                        <tr v-else v-for="item in items" :key="item.id"
                        >
                        <td>
                            <div v-html="item.item">
                                
                            </div>
                        </td>
                        <td></td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </div>
        </template>

    </content-container>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps([
    'items',
])


function edit() {
    router.get('/ched/program-application/gpr/edit');
}


</script>
