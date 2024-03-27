<template>

    <Head :title="institution.name" />
    <AdminPanel />
    <content-container pageTitle="Institution Details" :hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/admin/higher-education-institutions">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300 tooltipForActions" data-tooltip="Back">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:main-content>
            <div class="p-5 font-bold">
                {{ institution.name }}
            </div>
            <div class="p-5">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <th class="text-right align-top w-1/5 px-3 py-2 border border-gray-400">ID</th>
                            <td class="px-3 py-2 border border-gray-400">{{ institution.id }}</td>
                        </tr>
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
                                        <li v-if="institution.institution_program.length == 0">
                                            Empty
                                        </li>
                                        <li v-else v-for="program in institution.institution_program" :key="program.id">
                                            {{ program.program.program }}
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