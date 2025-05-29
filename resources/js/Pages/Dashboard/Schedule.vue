<template>
    <Head title="Schedule & Status" />
    <page-title title="Schedule & Status" />

    <div class="mx-3 md:mx-8 my-8 flex flex-col md:flex-row items-center justify-between">
        <div class="text font-semibold text-gray-700">
            Program Monitoring Schedule and Status
        </div>
        <div class="flex flex-row gap-2 mt-2 md:mt-0">
            
            <button
            v-if="$page.props.auth.user.role == 'Super Admin' || $page.props.auth.user.role == 'Supervising Education Program Specialist'"
            @click="toggleScheduleModal"
                class="inline-flex w-full items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none duration-200 ease-in-out shadow-sm"
            >
                Set Schedule
            </button>
        </div>
    </div>

    <div class="mx-3 md:mx-8 mb-10" v-if="$page.props.auth.user.role != 'Education Supervisor'">
        <div class="p-3 md:p-5 border border-gray-300 rounded-xl shadow-lg">
            <content-table>
                <template v-slot:table-head>
                    <th>HEI</th>
                    <th>Date</th>
                    <th>No. of Programs</th>
                    <th>No. of Programs Monitored</th>
                    <th>Percentage of Programs Monitored</th>
                    <th></th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="schedulesPerHEI.length === 0">
                        <no-search-result text="schedules" />
                    </tr>
                    <tr 
                        v-else
                        v-for="(item, index) in schedulesPerHEI" :key="item.id"
                    >
                        <td class="max-w-[18rem]">{{ item.institution }}</td>
                        <td class=" whitespace-nowrap">{{ item.monitoringDate || '-' }}</td>
                        <td>{{ item.totalPrograms }}</td>
                        <td>{{ item.programsMonitored }}</td>
                        <td>
                            <evaluation-progress :percentage="getPercentage(index)"/>
                        </td>
                        <td></td>
                    </tr>
                </template>
            </content-table>
        </div>
    </div>

    <div class="mx-3 md:mx-8">
        <div class="p-3 md:p-5 border border-gray-300 rounded-xl shadow-lg">
            <content-table>
                <template v-slot:table-head>
                    <th>HEI</th>
                    <th>Program</th>
                    <th>Date</th>
                    <th>Self-Evaluation Progress</th>
                    <th>Monitored?</th>
                    <th>Monitoring Compliance Status</th>
                    <th></th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="schedulesPerProgram.length === 0">
                        <no-search-result text="schedules" />
                    </tr>
                    <tr 
                        v-else
                        v-for="item in schedulesPerProgram" :key="item.id"
                    >
                        <td class="max-w-[18rem]">{{ item.institution }}</td>
                        <td class="max-w-[18rem]">{{ item.program }}</td>
                        <td class=" whitespace-nowrap">{{ item.monitoringDate || '-' }}</td>
                        <td>
                            <evaluation-progress :percentage="item.selfEvaluationProgress"/>
                        </td>
                        <td>
                            <status :text="item.isMonitored" :status="item.isMonitored" />
                        </td>
                        <td>
                            <evaluation-progress :percentage="item.compliancePercentage"/>
                        </td>
                        <td></td>
                    </tr>
                </template>
            </content-table>
        </div>
    </div>

    <div>

    </div>

    <modal
        :showModal="scheduleModal"
        @close="toggleScheduleModal"
        title="Set Schedule"
        width="lg"
    >
        <div>
            <div class="flex flex-col space-y-2 mb-6">
                <label for="isActive" class="text-sm font-medium text-gray-700">
                    HEI
                </label
                >
                <select
                    v-model="form.institution"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">Select HEI</option>
                    <option v-for="institution in institutions" :key="institution.id" :value="institution.id" >
                        {{ institution.name }}
                    </option>
                </select>
                <form-error-message
                    v-if="$page.props.errors && showError"
                    :message="$page.props.errors.institution"
                    theme="dark"
                />
            </div>
            <div class="flex flex-col space-y-2 mb-6">
                <label for="date" class="block text-sm font-medium text-gray-700">
                    Date
                </label>
                <input
                    type="date"
                    id="date"
                    v-model="form.date"
                    class="mt-1 block w-full px-3 py-2 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                <form-error-message
                    v-if="$page.props.errors && showError"
                    :message="$page.props.errors.date"
                    theme="dark"
                />
            </div>
            <div  class="flex flex-col space-y-2">
                <label for="ay" class="block text-sm font-medium text-gray-700">
                    Academic Year
                </label>
                <input
                    disabled
                    type="ay"
                    id="ay"
                    v-model="form.academicYear"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                />
                <form-error-message
                    v-if="$page.props.errors && showError"
                    :message="$page.props.errors.academicYear"
                    theme="dark"
                />
            </div>
        </div>
        <template #custom-button>
            <button @click="submit"
                    class="px-7 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg shadow-sm hover:bg-blue-600"
                >
                    Done
                </button>
        </template>
    </modal>

    
    <modal
        :showModal="showFilterModal"
        title="Filters"
        @close="toggleFilterModal"
        width="lg"
    >
        <div class="flex flex-col space-y-2">
            <label for="program" class="text-sm font-medium text-gray-700">
                Academic Year
            </label>
            <select
                v-model="query.academicyear"
                id="program"
                class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
            <option value="2023-2024">2023-2024</option>
            <option value="2024-2025">2024-2025</option>
            <option value="2025-2026">2025-2026</option>
            <option value="2026-2027">2026-2027</option>
            <option value="2027-2028">2027-2028</option>
            <option value="2028-2029">2028-2029</option>
            <option value="2029-2030">2029-2030</option>
            </select>
        </div>

        <template #custom-button>
            <div class="flex justify-end space-x-2">
                <button
                    @click="filter"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg shadow-sm hover:bg-blue-600"
                >
                    Apply
                </button>
            </div>
        </template>
    </modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { router } from '@inertiajs/vue3';
import Layout from '@/Shared/Layout.vue';
defineOptions({ layout: Layout });

const props = defineProps([
    "institutions", 
    "schedulesPerHEI",
    "schedulesPerProgram",
    "filters",
    "totalPrograms",
    "programsMonitored",
    "showFilter"
]);

const scheduleModal = ref(false);
const showError = ref(false);
const showFilterModal = ref(false);
const showFiltersApplied = ref(true);

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function toggleScheduleModal() {
    if (scheduleModal.value) {
        showError.value = false;
        form.institution = null;
        form.date = null;
    }

    scheduleModal.value = !scheduleModal.value;
}

const query = reactive({
    academicyear: ref(props.filters.academicyear),
});

const form = reactive({
    'institution': null,
    'date': null,
    'academicYear': ref(props.filters.academicyear),
});

function filter() {
    router.get("/ched/schedule", query, {
        preserveScroll: true,
        preserveState: false,
        replace: true,
    });
}

function submit() {
    router.post("/ched/schedule/set", form, {
        onError: () => {
            showError.value = true;
        },
        onSuccess: () => {
            toggleScheduleModal();
        },
        preserveScroll: true,
        preserveState: true,
        replace: true,
    })
}

const getPercentage = (index) => {
    let totalPrograms = props.schedulesPerHEI[index].totalPrograms;
    let programsMonitored = props.schedulesPerHEI[index].programsMonitored;

    if (totalPrograms === 0 || programsMonitored === 0) {
        return 0;
    }

    let result = (programsMonitored / totalPrograms) * 100;
    return result.toFixed(2);
};
</script>