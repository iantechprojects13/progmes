<template>

    <Head title="My Account" />
    <page-title title="My Account" />
    <content-container>
        <template v-slot:content-title>
                <div class="p-3 flex flex-row items-center justify-between border-b border-gray-400">
                    <div class="flex flex-row">
                        <div class="ml-2 mr-3">
                            <i class="fas fa-user text-blue-500"></i>
                        </div>
                        <div class="font-bold">Account Information</div>
                    </div>
                </div>
        </template>
        <template v-slot:main-content>
            <div class="my-8 mx-3 md:mx-5">
                <table class="border border-gray-400">
                    <tr>
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            Name</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">{{ profile.name }}</td>
                    </tr>
                    <tr>
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            Account
                            Type</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">
                            <div v-if="profile.type == 'CHED'">
                                Commission on Higher Education
                            </div>
                            <div v-else>
                                Higher Education Institution
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            Role</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">{{ profile.role }}</td>
                    </tr>
                    <tr v-show="hasInstitution">
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            HEI Name</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">
                            {{ getInstitution }}
                        </td>
                    </tr>
                    <tr v-show="hasDiscipline">
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            Discipline</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">
                            <div v-for="(item, index) in disciplineArray" :key="index">
                                {{ item }}
                            </div>
                        </td>
                    </tr>
                    <tr v-show="hasProgram">
                        <th scope="row"
                            class="px-3 py-1 align-top border-b border-gray-400 border-r font-bold text-right whitespace-nowrap">
                            Program</th>
                        <td class="px-3 py-1 align-top border-b border-gray-400 w-full">
                            {{getProgram}}
                        </td>
                    </tr>
                </table>
            </div>
        </template>
    </content-container>
</template>

<script setup>
    import { computed, ref } from 'vue';

    const props = defineProps([
        'profile',
        'roles',
        'isAdmin',
        'hasDiscipline',
        'hasInstitution',
        'hasProgram',
    ]);

    const disciplineArray = computed(() => {
        let discipline_list = [];

        if (props.hasDiscipline) {
            for (var i = 0; i < props.roles.length; i++) {
                discipline_list.push(props.roles[i].discipline.discipline);
            }
        }
        return discipline_list;
    });

    const getProgram = computed(() => {
        let program = '';
        if (props.hasProgram) {
            program = props.roles[0]?.program.program
        }
        return program;
    });

    const getInstitution = computed(() => {
        let institution = '';
        if (props.hasInstitution) {
            institution = props.roles[0]?.institution.name
        }
        return institution;
    });


</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>