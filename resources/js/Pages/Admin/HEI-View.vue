<template>

    <Head :title="institution.name" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
                <div class="w-full flex flex-row items-center justify-between">
                    <div class="flex flex-row">
                        <Link href="/admin/higher-education-institutions/">
                        <button><i class="fas fa-arrow-left ml-2 mr-3"></i></button>
                        </Link>
                        <div class="font-bold">HEI Details</div>
                    </div>
                </div>
        </template>
        <template v-slot:main-content>
            <div class="p-3 md:p-5">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <th class="text-right align-top w-1/5 px-3 py-2 border border-gray-400">Institutional
                                Code</th>
                            <td class="px-3 py-2 border border-gray-400">{{ institution.code }}</td>
                        </tr>
                        <tr>
                            <th class="text-right align-top w-1/5 px-3 py-2 border border-gray-400">Institution
                                Name</th>
                            <td class="px-3 py-2 border border-gray-400">{{ institution.name }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-8 mb-3 text-lg font-bold">
                    Program List
                </div>
                <content-table>
                    <template v-slot:table-head>
                        <th class="p-2 pl-3">Program</th>
                        <th class="p-2 pl-3">Major</th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="institution.active_program.length == 0">
                            <td colspan="2" class="py-10 items-center text-center">
                                No data found
                            </td>
                        </tr>

                        
                        <tr v-else v-for="(program, index) in institution.active_program" :key="program.id" :class="{'bg-gray-100': index % 2 == 1}" class="hover:bg-gray-200">
                            <td class="p-2 pl-3" >
                                {{ program.program.program }}
                            </td>
                            <td class="p-2 pl-3">
                                <div v-if="program.program?.major != null">
                                    {{ program.program?.major }}
                                </div>
                            </td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </template>
    </content-container>
</template>

<script setup>


    const props = defineProps([
        'institution',
    ]);


</script>

<script>
    import Layout from '@/Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>