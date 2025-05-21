<template>
    <Head title="My Account" />
    <page-title title="My Account" />
    <content-container :hasTopButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-col md:flex-row justify-between items-center">
                <div class="md:w-full">
                    
                </div>
                <div class="w-full md:w-auto">
                    <button @click="toggleDeleteAccountModal"
                        class="bg-blue-600 whitespace-nowrap w-full md:w-auto text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200"
                    >
                        Delete Account
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="mb-8 mx-3 md:mx-5 p-6 rounded-lg border border-gray-300 shadow-lg">
                <div class="flex flex-col items-center">
                    <!-- Profile Avatar -->
                    <img :src="profile.avatar" alt="User Profile" 
                        class="rounded-full border-4 border-gray-300 shadow-lg w-32 h-32">
                    <!-- Name and Email -->
                    <div class="text-gray-800 text-2xl mt-3 flex items-center">
                        <i class="fas fa-id-card ml-2 mr-4 text-blue-500"></i> {{ profile.name }}
                        <span class="text-base hover:text-blue-500 cursor-pointer tooltipForActions" data-tooltip="Edit Name" @click="toggleUpdateNameModal">
                            <i class="fas fa-edit ml-3"></i>
                        </span>
                    </div>
                    <p class="text-gray-600 flex items-center">
                        <i class="fas fa-envelope ml-2 mr-4 text-blue-500"></i> {{ profile.email }}
                    </p>
                </div>
                <!-- Account Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="profile.type" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-user-tag text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Type</h2>
                            <p class="text-gray-900">{{ profile.type }}</p>
                        </div>
                    </div>
                    <div v-if="profile.role" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-briefcase text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Role</h2>
                            <p class="text-gray-900">
                                <span v-if="profile.role == 'Vice-President for Academic Affairs'">
                                    VPAA/Dean of Multiple Discipline
                                </span>
                                <span v-else>
                                    {{ profile.role }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.institution" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-university text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Institution</h2>
                            <p class="text-gray-900">{{ roles[0].institution?.name }}</p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.discipline" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-book text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Discipline</h2>
                            <p v-for="role in roles" :key="role.id" class="text-gray-900">
                                {{ role.discipline?.discipline }}
                            </p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.program" class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-book text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Program</h2>
                            <p v-for="role in roles" :key="role.id" class="text-gray-900">
                                {{ role.program?.program }}
                                <span v-if="role.program?.major">
                                    - {{ role.program?.major }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <div v-show="hasDiscipline" class="mt-3">
                    <div class="text-gray-800 border-b border-gray-300 mb-2">
                        <p class="relative top-3 bg-white pr-3 w-fit">
                            Program Assignment
                        </p>
                    </div>
                    <div
                        class="shadow-md border border-gray-300 p-3 mt-5 rounded-lg"
                    >
                        <content-table>
                            <template v-slot:table-head>
                                <th>Program</th>
                                <th>Major</th>
                                <th></th>
                            </template>
                            <template v-slot:table-body>
                                <tr v-if="assignedPrograms.length === 0">
                                    <no-search-result text="program" />
                                </tr>

                                <tr
                                    v-else
                                    v-for="program in assignedPrograms"
                                    :key="program.id"
                                >
                                    <td>
                                        {{ program.program.program }}
                                    </td>
                                    <td>
                                        <span v-if="program.program?.major">{{
                                            program.program.major
                                        }}</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </template>
                        </content-table>
                    </div>
                </div>
            </div>
        </template>
    </content-container>

    <modal
        :showModal="updateNameModal"
        @close="toggleUpdateNameModal"
        title="Edit Name"
        width="md"
    >
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
                Name
            </label>
            <input
                type="text"
                id="name"
                v-model="form.name"
                class="mt-1 block w-full h-12 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                placeholder="Enter name"
            />
            <form-error-message
                v-if="$page.props.errors && showError"
                :message="$page.props.errors.name"
                theme="dark"
            />
        </div>
        <template #custom-button>
            <button
                @click="update"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
            >
                Update
            </button>
        </template>
    </modal>

    <confirmation
        :showModal="deleteAccountModal"
        @close="toggleDeleteAccountModal"
        title="Delete Account"
        width="lg"
    >
        <template #message>
            <div class="text-gray-700 space-y-2">
                <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                <p class="mt-8 text-sm">
                    Note: This will NOT delete any of your submitted inputs or data.
                </p>
            </div>
        </template>
        <template #buttons>
            <button
                @click="deleteAccount"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
            >
                Delete
            </button>
        </template>
    </confirmation>

</template>

<script setup>
import { reactive, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';

defineOptions({
    layout: Layout,
});

const props = defineProps(['profile', 'roles', 'hasDiscipline', 'assignedPrograms']);

const updateNameModal = ref(false);
const deleteAccountModal = ref(false);
const showError = ref(false);

const form = reactive({
    name: props.profile.name,
});

function toggleUpdateNameModal() {
    if (updateNameModal.value) {
        showError.value = false;
    }
    updateNameModal.value = !updateNameModal.value;
}

function toggleDeleteAccountModal() {
    deleteAccountModal.value = !deleteAccountModal.value;
}

watch(updateNameModal, (newVal) => {
    if (!newVal) {
        form.name = props.profile.name;
    }
});

function update() {
    router.post('/myaccount/update-name', form, {
        onSuccess: () => {
            toggleUpdateNameModal();
        },
        onError: () => {
            showError.value = true;
        },
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
}

function deleteAccount() {
    router.post('/myaccount/delete', {
        preserveState: false,
        replace: true,
    });
}

</script>
