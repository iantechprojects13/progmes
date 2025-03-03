<template>
    <Head :title="institution.name" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between border-b pb-3 bg-gray-200 text-gray-800 p-4 rounded-t-xl">
                <div class="flex flex-row items-center">
                    <Link href="/admin/higher-education-institutions/">
                        <button class="text-gray-800 hover:text-gray-600">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </button>
                    </Link>
                    <h1 class="text-xl font-semibold ml-3">HEI Details</h1>
                </div>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="p-6 bg-gray-100 shadow-lg rounded-b-xl text-gray-900">
                <table class="w-full border rounded-lg overflow-hidden shadow-md bg-white">
                    <tbody>
                        <tr class="bg-gray-200 border-b">
                            <th class="text-left align-top w-1/4 px-6 py-4 font-medium text-gray-700">
                                <i class="fas fa-university mr-2"></i> Institutional Code
                            </th>
                            <td class="px-6 py-4 text-gray-900">{{ institution.code }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="text-left align-top w-1/4 px-6 py-4 font-medium text-gray-700">
                                <i class="fas fa-building mr-2"></i> Institution Name
                            </th>
                            <td class="px-6 py-4 text-gray-900">{{ institution.name }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="mt-8 mb-4 text-lg font-bold text-gray-800 border-b pb-2">
                    <i class="fas fa-list-ul mr-2"></i> Program List
                </div>
                <content-table>
                    <template v-slot:table-head>
                        <th class="p-4 text-left bg-gray-300 text-gray-800"><i class="fas fa-book mr-2"></i> Program</th>
                        <th class="p-4 text-left bg-gray-300 text-gray-800"><i class="fas fa-user-graduate mr-2"></i> Major</th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="institution.active_program.length === 0">
                            <td colspan="2" class="py-10 text-center text-gray-500">No data found</td>
                        </tr>
                        <tr v-else v-for="(program, index) in institution.active_program" :key="program.id" 
                            :class="{'bg-gray-100': index % 2 === 0, 'bg-gray-200': index % 2 !== 0}" 
                            class="hover:bg-gray-300 transition ease-in-out duration-200 border-b">
                            <td class="p-4 text-gray-900">{{ program.program.program }}</td>
                            <td class="p-4 text-gray-900">
                                <span v-if="program.program?.major">{{ program.program.major }}</span>
                            </td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </template>
    </content-container>
</template>

<script setup>
    const props = defineProps(['institution']);
</script>

<script>
    import Layout from '@/Shared/Layout.vue';
    export default {
        layout: Layout,
    };
</script>