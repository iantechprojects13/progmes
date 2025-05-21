<template>
    <Head :title="library_cmo" />
    <content-container :hasAdminPanel="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center">
                <Link href="/admin/library/CMO/view">
                    <button
                        class="w-8 h-8 mr-3 rounded-full hover:bg-gray-200 tooltipForActions" data-tooltip="Back">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </button>
                </Link>
                <div class="font-bold">
                    {{ library_cmo }}
                </div>
            </div>
            <div>
                <button 
                    @click="update" ref="saveBtn"
                    class="whitespace-nowrap w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    Save Changes
                </button>
            </div>
        </template>
        <template v-slot:main-content>
            <div class="md:p-3">
                <div class="md:p-3 md:border rounded-xl md:shadow-lg">
                    <content-table>
                        <template v-slot:table-head>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Item No
                            </th>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Area
                            </th>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Minimum Requirement
                            </th>
                            <th class="px-6 py-4 text-right w-24">
                                <span class="text-gray-800">
                                    <i class="fas fa-ellipsis-v text-lg"></i>
                                </span>
                            </th>
                        </template>
                        <template v-slot:table-body>
                            <tr v-if="items.length == 0">
                                <no-search-result text="items" />
                            </tr>
                            <tr v-else v-for="item in items" :key="item.id">
                                <td>
                                    {{ item.no }}
                                </td>
                                <td>
                                    <tip-tap-editor v-model="item.area" @input="updateData" @update="update" />
                                </td>
                                <td>
                                    <tip-tap-editor v-model="item.minimumRequirement" @input="updateData" @update="update" />
                                </td>
                                <td>
                                    <action-button 
                                        v-if="!item.id.toString().startsWith('temp-')" 
                                        type="delete" 
                                        @click="itemId = item.id; toggleDeleteModal()" 
                                    />
                                    <action-button 
                                        v-else
                                        type="remove" 
                                        @click="removeTemporaryRow(item.id)" 
                                    />
                                </td>
                            </tr>
                            <!-- Add Row Button -->
                            <tr class="border-b border-gray-200">
                                <td colspan="100%" class="px-6 py-4">
                                    <button
                                        @click="addRow"
                                        class="w-44 flex items-center justify-center py-2 px-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-4 focus:ring-green-200"
                                    >
                                        <i class="fas fa-plus mr-2"></i> Add Row
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </content-table>
                </div>
            </div>
        </template>
    </content-container>


    <confirmation
        title="Delete"
        :showModal="deleteModal"
        width="md"
        @close="toggleDeleteModal"
    >
        <template v-slot:message>
            <p class="text-gray-800 text-base">
                Are you sure you want to delete this item?
            </p>
        </template>
        <template v-slot:buttons>
            <Link :href="`/admin/library/CMO/${itemId}/delete`">
                <modal-button text="Delete" color="red" />
            </Link>
        </template>
    </confirmation>
</template>

<script setup>
import { reactive, ref, inject, onUnmounted, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

const props = defineProps([
    'items',
    'library_cmo'
]);

const saveBtn = ref(null);
const items = ref([...props.items]);
const deleteModal = ref(false);
const itemId = ref(null);

function toggleDeleteModal() {
    deleteModal.value = !deleteModal.value;
}

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
};

// Function to add a new row
const addRow = () => {
    const newItemNo = items.value.length > 0 
        ? Math.max(...items.value.map(item => parseInt(item.no))) + 1 
        : 1;
    
    items.value.push({
        id: `temp-${Date.now()}`,
        no: newItemNo.toString(),
        area: '',
        minimumRequirement: ''
    });
    
    updateData();
};

const removeTemporaryRow = (tempId) => {
    items.value = items.value.filter(item => item.id !== tempId);
    updateData();
};


const refs = { saveBtn };

// CTRL + S function
const handleKeyDown = (event) => {
    if (event.ctrlKey || event.metaKey) {
        if (event.key === 's' || event.key === 'S') {
            event.preventDefault();
            refs.saveBtn.value.click();
        }
    }
};

const handleBeforeUnload = (event) => {
    if (hasUnsavedChanges.value) {
        event.returnValue = true;
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
    window.addEventListener('beforeunload', handleBeforeUnload);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    window.removeEventListener('beforeunload', handleBeforeUnload);
    hasUnsavedChanges.value = false;
});

const form = reactive({
    items: items,
});

function update() {
    router.post('/admin/library/CMO/update', form, {
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}

</script>
