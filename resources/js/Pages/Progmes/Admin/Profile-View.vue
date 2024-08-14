<template>

    <Head title="View Profile" />
    <AdminPanel />
    <content-container>
        <template v-slot:content-title>
                <div class="w-full flex flex-row items-center justify-between">
                    <div class="flex flex-row">
                        <Link href="/admin/users/list/">
                        <button><i class="fas fa-arrow-left ml-2 mr-3"></i></button>
                        </Link>
                        <div class="font-bold">Profile Details</div>
                    </div>
                    <div v-show="$page.props.auth.user.role == 'Super Admin'"><button @click="toggleChangeRoleModal" class="h-10 px-2 bg-gray-700 text-white hover:bg-gray-800 rounded">Change role</button></div>
                </div>
        </template>
        <template v-slot:main-content>
            <div class="m-10">
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
    <modal :showModal="showModal" @close="toggleChangeRoleModal" title="Change User Role" height="short" width="lg">
        <div>Are you sure you want to change the role for this user? This action will deactivate their current role.</div>
        <template v-slot:custom-button>
            <button @click="changeRole" class="h-10 w-20 rounded bg-blue-500 text-white">
                Confirm
            </button>
        </template>
    </modal>
</template>

<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';

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

const showModal = ref(false);

function toggleChangeRoleModal() {
    showModal.value = !showModal.value;
}

function changeRole() {
    router.post('/register/change-role', { 'userId': props.profile.id })
}


</script>

<script>
    import Layout from '../Shared/Layout.vue';
    export default {
        layout: Layout,
    }
</script>