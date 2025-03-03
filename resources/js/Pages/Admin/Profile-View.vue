<template>
    <Head title="View Profile" />
    <AdminPanel />
    <content-container>
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between bg-white p-4 shadow rounded-lg">
                <div class="flex flex-row items-center">
                    <Link href="/admin/users/list/">
                        <button class="text-gray-600 hover:text-gray-800 w-12 h-12 hover:bg-gray-200 rounded-full tooltipForActions" data-tooltip="Back to User List">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </button>
                    </Link>
                    <div class="font-bold text-xl ml-3 text-gray-700">Profile Details</div>
                </div>
                <div v-show="$page.props.auth.user.role == 'Super Admin'">
                    <button @click="toggleChangeRoleModal" class="h-10 px-4 bg-blue-500 text-white hover:bg-blue-600 rounded-lg shadow-md">
                        <i class="fas fa-user-cog mr-2"></i> Change Role
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="m-10 p-6 bg-gray-100 rounded-lg shadow-lg">
                <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
                    <tbody>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-user mr-2"></i> Name
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ profile.name }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-envelope mr-2"></i> Email
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ profile.email }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-id-card mr-2"></i> Account Type
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ profile.type }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-user-tag mr-2"></i> Role
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ profile.role }}</td>
                        </tr>
                        <tr v-show="hasInstitution" class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-university mr-2"></i> HEI Name
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ getInstitution }}</td>
                        </tr>
                        <tr v-show="hasDiscipline" class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-book mr-2"></i> Discipline
                            </th>
                            <td class="px-4 py-3 text-gray-800">
                                <span v-for="(item, index) in disciplineArray" :key="index">{{ item }}</span>
                            </td>
                        </tr>
                        <tr v-show="hasProgram" class="border-b">
                            <th class="px-4 py-3 text-left text-gray-700 font-semibold bg-gray-200">
                                <i class="fas fa-graduation-cap mr-2"></i> Program
                            </th>
                            <td class="px-4 py-3 text-gray-800">{{ getProgram }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </content-container>
    <modal :showModal="showModal" @close="toggleChangeRoleModal" title="Change User Role" height="short" width="lg">
        <div class="text-gray-700">Are you sure you want to change the role for this user? This action will deactivate their current role.</div>
        <template v-slot:custom-button>
            <button @click="changeRole" class="h-10 w-24 rounded bg-red-500 text-white hover:bg-red-600 shadow-md">
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
            discipline_list.push(props.roles[i].discipline?.discipline);
        }
    }
    return discipline_list;
});

const getProgram = computed(() => {
    let program = '';
    if (props.hasProgram) {
        program = props.roles[0]?.program?.program;
    }
    return program;
});

const getInstitution = computed(() => {
    let institution = '';
    if (props.hasInstitution) {
        institution = props.roles[0]?.institution.name;
    }
    return institution;
});

const showModal = ref(false);

function toggleChangeRoleModal() {
    showModal.value = !showModal.value;
}

function changeRole() {
    router.post('/register/change-role', { userId: props.profile.id });
}
</script>

<script>
import Layout from '@/Shared/Layout.vue';
export default {
    layout: Layout,
};
</script>