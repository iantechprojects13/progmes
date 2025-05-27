<template>
    <Head title="GPR" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="flex flex-row items-center justify-between w-full">
                <div class="w-full flex flex-row items-center">
                    <Link href="/admin/library/">
                        <button
                            class="w-8 h-8 mr-3 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </button>
                    </Link>
                    <div class="font-bold">
                        CMO {{ library_cmo }}
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center gap-2" v-show="canEdit">
                    <button
                        @click="toggleDeployModal"
                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 focus:outline-none duration-200 whitespace-nowrap"
                    >
                        <svg
                            class="w-4 h-4 mr-2"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M12 2L4 12H9V22H15V12H20L12 2Z"
                                fill="currentColor"
                            />
                        </svg>
                        Deploy Tool
                    </button>
                    <button
                        @click="edit"
                        class="w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="p-3">
            <div class="p-3 border rounded-xl shadow-lg">
                <content-table>
                    <template v-slot:table-head>
                        <th>Item No</th>
                        <th>Area</th>
                        <th>Minimum Requirement</th>
                        <th></th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="items.length == 0">
                            <no-search-result text="items" />
                        </tr>
                        <tr v-else v-for="item in items" :key="item.id"
                        >
                        <td>
                            {{ item.no }}
                        </td>
                        <td>
                            <div v-html="item.area">
                                
                            </div>
                        </td>
                        <td>
                            <div v-html="item.minimumRequirement">
                                
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

    <modal
        :showModal="deployToolModal"
        width="md"
        @close="toggleDeployModal"
        @submit="deploy"
        type="deploy"
        title="Deploy Compliance Tool"
    >
        <div class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Effective A.Y.
                </label>
                <select
                    v-model="deployment.effectivity"
                    id="toolEffectivity"
                    class="w-full text-sm rounded border-gray-400 h-12"
                >
                    <option
                        v-for="(year, index) in academicYearDropdown"
                        :key="index"
                        :value="year"
                    >
                        {{ year }}
                    </option>
                </select>
            </div>
        </div>
        <template v-slot:custom-button>
            <button
                :disabled="
                    deployment.effectivity == null
                "
                @click="toggleConfirmationModal"
                class="px-3 py-2 text-sm font-medium text-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
                :class="[
                    deployment.effectivity == null
                        ? 'bg-gray-400 cursor-not-allowed'
                        : 'bg-blue-600 hover:bg-blue-700',
                ]"
            >
                Deploy
            </button>
        </template>
    </modal>

    <Confirmation
        :showModal="confirmationModal"
        @close="toggleConfirmationModal"
        title="Deploy Compliance Tool"
        width="md"
    >
        <template v-slot:message>
            <div>
                Are you sure you want to deploy compliance evaluation tool for library?
            </div>
        </template>
        <template v-slot:buttons>
            <button
                @click="deploy"
                class="px-3 py-2 text-sm font-medium text-white rounded-md shadow-sm bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:text-sm"
            >
                Confirm
            </button>
        </template>
    </Confirmation>

</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps([
    'items',
    'effectivity',
    'library_cmo',
    'canEdit',
])

const deployToolModal = ref(false);
const confirmationModal = ref(false);
const academicYearDropdown = [
    "2022-2023",
    "2023-2024",
    "2024-2025",
    "2025-2026",
    "2026-2027",
    "2027-2028",
    "2028-2029",
    "2029-2030",
];

const deployment = useForm({
    effectivity: ref(props.effectivity),
});

function toggleDeployModal() {
    deployToolModal.value = !deployToolModal.value;
}

function toggleConfirmationModal() {
    if (confirmationModal.value == true) {
        setTimeout(() => {
            toggleDeployModal();
        }, 297);
    } else {
        toggleDeployModal();
    }

    if (confirmationModal.value == false) {
        setTimeout(() => {
            confirmationModal.value = !confirmationModal.value;
        }, 297);
    } else {
        confirmationModal.value = !confirmationModal.value;
    }
}

function deploy() {
    router.post("/admin/library/CMO/deploy", deployment, {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

function edit() {
    router.get('/admin/library/CMO/edit');
}

</script>