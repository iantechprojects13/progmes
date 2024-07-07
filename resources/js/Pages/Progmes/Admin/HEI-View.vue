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
                        <tr>
                            <th class="text-right align-top w-1/5 px-3 py-2 border border-gray-400">Programs</th>
                            <td class="px-3 py-2 border border-gray-400">
                                <div>
                                    <ul>
                                        <li v-if="institution.active_program.length == 0">
                                            Empty
                                        </li>
                                        <li v-else v-for="(program, index) in institution.active_program" :key="program.id">
                                            {{index+1}}. {{ program.program.program }}
                                            <span v-if="program.program?.major != null">
                                                - {{ program.program?.major }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
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
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>