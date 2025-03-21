<template>
    <Head title="GPR" />
    <page-title title="Program Application" />
    <content-container>
        <template v-slot:content-title>
            <div class="w-full flex flex-row items-center justify-between text-gray-800">
                <div class="flex flex-row items-center">
                    <Link href="/ched/program-application/gpr">
                        <button class="text-gray-800 hover:text-gray-600 w-6 tooltipForActions" data-tooltip="Back">
                            <i class="fas fa-arrow-left text-lg"></i>
                        </button>
                    </Link>
                    <h1 class="text-lg font-semibold ml-3">GPR</h1>
                </div>
            </div>
            <div>
                <button 
                        @click="update" ref="saveBtn"
                        class=" whitespace-nowrap w-full bg-blue-600 text-white py-2 px-6 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200"
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
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="items.length == 0">
                            <no-search-result text="items" />
                        </tr>
                        <tr v-else v-for="(item, index) in items" :key="item.id"
                        class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{
                            'bg-slate-200': index % 2 == 1,
                        }"
                        >
                        <td class="px-6 py-2 text-base text-gray-900">
                            {{ item.no }}
                        </td>
                        <td class="px-6 py-2 text-base text-gray-900">
                            <tip-tap-editor v-model="item.item" @input="updateData" />
                        </td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </div>
        </template>
    </content-container>
</template>

<script setup>
import { reactive, ref, computed, inject, onUnmounted, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps([
    'items',
])

const saveBtn = ref(null);

// Inject the reactive object
const hasUnsavedChanges = inject("hasUnsavedChanges");

// Function to update value
const updateData = () => {
    hasUnsavedChanges.value = true;
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



const itemsArray = computed(() => {
    return props.items.map(item => ({
        id: item.id,
        no: item.no,
        item: item.item,
    }));
});



const form = reactive({
    items: ref(itemsArray),
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

<script>
    import Layout from '@/Shared/Layout.vue';

    export default {
        layout: Layout,
    }
    
</script>