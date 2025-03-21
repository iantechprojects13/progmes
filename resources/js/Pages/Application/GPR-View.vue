<template>
    <Head title="GPR" />
    <page-title title="Program Application" />
    <content-container>
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between text-gray-800">
                <div class="flex flex-row items-center">
                    <Link href="/application">
                        <button class="text-gray-800 hover:text-gray-600 w-6 tooltipForActions" data-tooltip="Back">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </button>
                    </Link>
                    <h1 class="text-lg font-semibold ml-3">GPR</h1>
                </div>
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
                        <th
                            class="px-6 py-4 text-left text-base font-bold text-gray-800"
                        >
                            Item No.
                        </th>
                        <th
                            class="px-6 py-4 text-left text-base font-bold text-gray-800"
                        >
                            Documents
                        </th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="items.length == 0">
                            <no-search-result text="items" />
                        </tr>
                        <tr v-else v-for="(item, index) in items" :key="item.id"
                        class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{
                            'bg-slate-200': index % 2 == 1,
                        }"
                        >
                        <td class="px-6 py-2 text-base text-gray-900">
                            {{ item.no }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            <div v-html="item.item">
                                
                            </div>
                        </td>
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

const props = defineProps([
    'items',
])


function edit() {
    router.get('/ched/program-application/gpr/edit');
}


</script>

<script>
    import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>