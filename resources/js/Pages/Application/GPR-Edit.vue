<template>
    <Head title="GPR" />
    <page-title title="Program Application" />
    <content-container :hasBackButton="true">
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between">
                <div class="font-bold">GPR</div>
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
            <div class="p-3">
                <div class="p-3 border rounded-xl shadow-lg">
                    <content-table>
                        <template v-slot:table-head>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Item No.
                            </th>
                            <th
                                class="px-6 py-4 text-left text-base font-bold text-gray-800"
                            >
                                Documents
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
                                    <tip-tap-editor v-model="item.item" @input="updateData" />
                                </td>
                                <td>
                                    <button @click="itemId = item.id; toggleDeleteModal()"
                                        class="inline-flex items-center justify-center rounded-full h-10 w-10 text-red-700 hover:bg-red-100 transition-colors tooltipForActions"
                                        data-tooltip="Delete"
                                    >
                                        <i class="fas fa-trash text-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            <!-- Add Row Button -->
                            <tr class="border-b border-gray-200">
                                <td colspan="3" class="px-6 py-4">
                                    <button
                                        @click="addRow"
                                        class="w-full flex items-center justify-center py-2 px-4 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors focus:outline-none focus:ring-4 focus:ring-green-200"
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
            <Link :href="`/ched/program-application/gpr/${itemId}/delete`">
                <button class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 h-10 w-20">
                    Delete
                </button>
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
        item: ''
    });
    
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
});

const form = reactive({
    items: items,
});

function update() {
    router.post('/ched/program-application/gpr/update', form, {
        onSuccess: () => {
            hasUnsavedChanges.value = false;
        },
        preserveState: false,
        preserveScroll: true,
    });
}
</script>
