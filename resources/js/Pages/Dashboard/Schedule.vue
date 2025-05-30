<template>
    <Head title="Schedule & Status" />
    <page-title title="Schedule & Status" />
    <div
        class="mx-3 md:mx-8 my-8 flex flex-col md:flex-row items-center justify-between"
    >
        <div class="text font-semibold text-gray-700">
            Program Monitoring Schedule and Status
        </div>
        <div class="flex flex-row gap-5 mt-2 md:mt-0">
            <!-- View Toggle Buttons -->
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button
                    @click="currentView = 'list'"
                    :class="[
                        ' whitespace-nowrap px-3 py-1.5 font-medium rounded-md transition-colors duration-200',
                        currentView === 'list'
                            ? 'bg-white text-blue-600 shadow-sm'
                            : 'text-gray-600 hover:text-gray-900',
                    ]"
                >
                    List View
                </button>
                <button
                    @click="currentView = 'calendar'"
                    :class="[
                        ' whitespace-nowrap px-3 py-1.5 font-medium rounded-md transition-colors duration-200',
                        currentView === 'calendar'
                            ? 'bg-white text-blue-600 shadow-sm'
                            : 'text-gray-600 hover:text-gray-900',
                    ]"
                >
                    Calendar View
                </button>
            </div>

            <button
                v-if="
                    $page.props.auth.user.role == 'Super Admin' ||
                    $page.props.auth.user.role ==
                        'Supervising Education Program Specialist'
                "
                @click="toggleScheduleModal"
                class="inline-flex w-full items-center justify-center px-4 py-2.5 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none duration-200 ease-in-out shadow-sm"
            >
                Set Schedule
            </button>
        </div>
    </div>

    <!-- Calendar View -->
    <div v-if="currentView === 'calendar'" class="mx-3 md:mx-8 mb-10">
        <div
            class="p-3 md:p-5 border border-gray-300 rounded-xl shadow-lg bg-white"
        >
            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ getMonthName(currentMonth) }} {{ currentYear }}
                </h3>
                <div class="flex space-x-2">
                    <button
                        @click="previousMonth"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors tooltipForActions"
                        data-tooltip="Previous Month"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                    </button>
                    <button
                        @click="nextMonth"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors tooltipForActions"
                        data-tooltip="Next Month"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 gap-1">
                <!-- Day Headers -->
                <div
                    v-for="day in [
                        'Sun',
                        'Mon',
                        'Tue',
                        'Wed',
                        'Thu',
                        'Fri',
                        'Sat',
                    ]"
                    :key="day"
                    class="p-3 text-center font-medium text-gray-500 border-b border-gray-200"
                >
                    {{ day }}
                </div>

                <!-- Calendar Days -->
                <div
                    v-for="day in calendarDays"
                    :key="`${day.date}-${day.isCurrentMonth}`"
                    :class="[
                        'min-h-[120px] p-2 border border-gray-100',
                        { 'bg-gray-100': !day.isCurrentMonth },
                        { 'border-gray-300': day.isCurrentMonth },
                        { 'bg-green-200': day.isCurrentMonth && day.isToday },
                    ]"
                >
                    <!-- Day Number -->
                    <div
                        :class="[
                            'font-medium mb-2',
                            { 'text-gray-900': day.isCurrentMonth },
                            { 'text-gray-400': !day.isCurrentMonth },
                            { 'text-blue-600': day.isToday },
                        ]"
                    >
                        {{ day.date }}
                    </div>

                    <!-- Institution Names -->
                    <div
                        v-for="institution in day.institutions"
                        :key="institution"
                        class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded mb-1 break-words"
                    >
                        {{ institution }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List View (Original Content) -->
    <div v-if="currentView === 'list'">
        <div
            class="mx-3 md:mx-8 mb-10"
            v-if="$page.props.auth.user.role != 'Education Supervisor'"
        >
            <div class="p-3 md:p-5 border border-gray-300 rounded-xl shadow-lg">
                <content-table>
                    <template v-slot:table-head>
                        <th>HEI</th>
                        <th>Schedule of Validation</th>
                        <th>No. of Programs</th>
                        <th>No. of Programs Evaluated</th>
                        <th>Percentage of Programs Evaluated</th>
                        <th>
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="schedulesPerHEI.length === 0">
                            <no-search-result text="schedules" />
                        </tr>
                        <tr
                            v-else
                            v-for="(item, index) in schedulesPerHEI"
                            :key="item.id"
                        >
                            <td class="max-w-[18rem]">
                                {{ item.institution }}
                            </td>
                            <td class="whitespace-nowrap">
                                {{ item.monitoringDate || "-" }}
                            </td>
                            <td>{{ item.totalPrograms }}</td>
                            <td>{{ item.programsMonitored }}</td>
                            <td>
                                <evaluation-progress
                                    :percentage="getPercentage(index)"
                                />
                            </td>
                            <td>
                                <action-button
                                    type="remove"
                                    :isDisabled="item.id == null"
                                    @click="toggleDeleteModal(item.id)"
                                />
                            </td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </div>
        <div class="mx-3 md:mx-8" v-show="false">
            <div class="p-3 md:p-5 border border-gray-300 rounded-xl shadow-lg">
                <content-table>
                    <template v-slot:table-head>
                        <th>HEI</th>
                        <th>Program</th>
                        <th>Date</th>
                        <th>Self-Evaluation Progress</th>
                        <th>Monitoring Compliance Status</th>
                        <th>Evaluation?</th>
                        <th></th>
                    </template>
                    <template v-slot:table-body>
                        <tr v-if="schedulesPerProgram.data.length === 0">
                            <no-search-result text="schedules" />
                        </tr>
                        <tr
                            v-else
                            v-for="item in schedulesPerProgram.data"
                            :key="item.id"
                        >
                            <td class="max-w-[18rem]">
                                {{ item.institution }}
                            </td>
                            <td class="max-w-[18rem]">{{ item.program }}</td>
                            <td class="whitespace-nowrap">
                                {{ item.monitoringDate || "-" }}
                            </td>
                            <td>
                                <evaluation-progress
                                    :percentage="item.selfEvaluationProgress"
                                />
                            </td>
                            <td>
                                <evaluation-progress
                                    :percentage="item.compliancePercentage"
                                />
                            </td>
                            <td>
                                <status
                                    :text="item.isMonitored"
                                    :status="item.isMonitored"
                                />
                            </td>
                            <td></td>
                        </tr>
                    </template>
                </content-table>
            </div>
        </div>
    </div>

    <content-container
        v-if="currentView === 'list'"
        :hasSearch="true"
        :hasFilters="true"
        :data_list="schedulesPerProgram"
        :hideTopBorder="true"
    >
        <template v-slot:options>
            <options
                @filter="toggleFilterModal"
                href="/ched/schedule"
            />
        </template>
    <template v-slot:main-content>
        <content-table>
            <template v-slot:table-head>
                <th>HEI</th>
                <th>Program</th>
                <th>Date</th>
                <th>Self-Evaluation Progress</th>
                <th>Monitoring Compliance Status</th>
                <th>Evaluation?</th>
                <th></th>
            </template>
            <template v-slot:table-body>
                <tr v-if="schedulesPerProgram.data.length === 0">
                    <no-search-result text="schedules" />
                </tr>
                <tr
                    v-else
                    v-for="item in schedulesPerProgram.data"
                    :key="item.id"
                >
                    <td class="max-w-[18rem]">
                        {{ item.institution }}
                    </td>
                    <td class="max-w-[18rem]">{{ item.program }}</td>
                    <td class="whitespace-nowrap">
                        {{ item.monitoringDate || "-" }}
                    </td>
                    <td>
                        <evaluation-progress
                            :percentage="item.selfEvaluationProgress"
                        />
                    </td>
                    <td>
                        <evaluation-progress
                            :percentage="item.compliancePercentage"
                        />
                    </td>
                    <td>
                        <status
                            :text="item.isMonitored"
                            :status="item.isMonitored"
                        />
                    </td>
                    <td></td>
                </tr>
            </template>
        </content-table>
        </template>
        </content-container>

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
                </label>
                <select
                    v-model="form.institution"
                    id="isActive"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">Select HEI</option>
                    <option
                        v-for="institution in institutions"
                        :key="institution.id"
                        :value="institution.id"
                    >
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
                <label
                    for="date"
                    class="block text-sm font-medium text-gray-700"
                >
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
            <div class="flex flex-col space-y-2">
                <label for="ay" class="block text-sm font-medium text-gray-700">
                    Academic Year
                </label>
                <select
                    v-model="form.academicYear"
                    id="ay"
                    class="block w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="2024-2025">2024-2025</option>
                    <option value="2025-2026">2025-2026</option>
                    <option value="2026-2027">2026-2027</option>
                    <option value="2027-2028">2027-2028</option>
                    <option value="2028-2029">2028-2029</option>
                    <option value="2029-2030">2029-2030</option>
                </select>
                <form-error-message
                    v-if="$page.props.errors && showError"
                    :message="$page.props.errors.academicYear"
                    theme="dark"
                />
            </div>
        </div>
        <template #buttons>
            <modal-button text="Done" color="blue" @click="submit" />
        </template>
    </modal>

    <modal
        :showModal="showFilterModal"
        @close="toggleFilterModal"
        title="Filters"
        width="md"
    >
        <div class="space-y-4">
            <filter-page-items v-model="query.show" />
        </div>

        <template #custom-button>
            <modal-button text="Apply" color="green" @click="filter" />
        </template>
    </modal>

    <confirmation
        :showModal="deleteModal"
        @close="toggleDeleteModal"
        title="Delete Schedule"
        width="md"
    >
        <template #message>
            Are you sure you want to delete this schedule?
        </template>
        <template #buttons>
            <modal-button
                text="Delete"
                color="red"
                @click="deleteSched"
            />
        </template>
    </confirmation>
</template>

<script setup>
import { ref, reactive, computed } from "vue";
import { router } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";

defineOptions({ layout: Layout });

const props = defineProps([
    "institutions",
    "schedulesPerHEI",
    "schedulesPerProgram",
    "filters",
    "totalPrograms",
    "programsMonitored",
    "showFilter",
    "academicYear",
]);

const scheduleModal = ref(false);
const showError = ref(false);
const selectedSched = ref(null);
const deleteModal = ref(false);
const showFilterModal = ref(false);

// View toggle
const currentView = ref("list");

// Calendar state
const currentMonth = ref(new Date().getMonth());
const currentYear = ref(new Date().getFullYear());

function toggleScheduleModal() {
    if (scheduleModal.value) {
        showError.value = false;
        form.institution = null;
        form.date = null;
    }
    scheduleModal.value = !scheduleModal.value;
}

function toggleDeleteModal(id) {
    if (deleteModal.value) {
        selectedSched.value = null;
    } else {
        selectedSched.value = id;
    }
    deleteModal.value = !deleteModal.value;
}

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

const query = reactive({
    search: props.filters.search,
    show: props.filters.show,
    academicyear: props.filters.academicyear,
});

const form = reactive({
    institution: null,
    date: null,
    academicYear: ref(props.academicYear),
});

function filter() {
    router.get("/ched/schedule", query, {
        onSuccess: () => {
            showFilterModal.value = false;
            currentView.value = "list";
        },
        preserveScroll: true,
        preserveState: true,
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
    });
}

function deleteSched() {
    router.post("/ched/schedule/delete", {
        id: selectedSched.value,
    }, {
        onSuccess: () => {
            toggleDeleteModal();
        },
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
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

// Calendar functions
function getMonthName(monthIndex) {
    const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ];
    return months[monthIndex];
}

function previousMonth() {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
}

function nextMonth() {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
}

function getDaysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}

function getFirstDayOfMonth(month, year) {
    return new Date(year, month, 1).getDay();
}

function formatDate(year, month, day) {
    const monthStr = String(month + 1).padStart(2, "0");
    const dayStr = String(day).padStart(2, "0");
    return `${year}-${monthStr}-${dayStr}`;
}

function isToday(year, month, day) {
    const today = new Date();
    return (
        today.getFullYear() === year &&
        today.getMonth() === month &&
        today.getDate() === day
    );
}

// Create calendar days with institution names
const calendarDays = computed(() => {
    const days = [];
    const daysInMonth = getDaysInMonth(currentMonth.value, currentYear.value);
    const firstDay = getFirstDayOfMonth(currentMonth.value, currentYear.value);

    // Previous month's trailing days
    const prevMonth = currentMonth.value === 0 ? 11 : currentMonth.value - 1;
    const prevYear =
        currentMonth.value === 0 ? currentYear.value - 1 : currentYear.value;
    const daysInPrevMonth = getDaysInMonth(prevMonth, prevYear);

    for (let i = firstDay - 1; i >= 0; i--) {
        const day = daysInPrevMonth - i;
        const dateStr = formatDate(prevYear, prevMonth, day);
        const institutions = getInstitutionsForDate(dateStr);

        days.push({
            date: day,
            isCurrentMonth: false,
            isToday: isToday(prevYear, prevMonth, day),
            institutions: institutions,
        });
    }

    // Current month days
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = formatDate(currentYear.value, currentMonth.value, day);
        const institutions = getInstitutionsForDate(dateStr);

        days.push({
            date: day,
            isCurrentMonth: true,
            isToday: isToday(currentYear.value, currentMonth.value, day),
            institutions: institutions,
        });
    }

    // Next month's leading days
    const totalCells = Math.ceil(days.length / 7) * 7;
    const nextMonth = currentMonth.value === 11 ? 0 : currentMonth.value + 1;
    const nextYear =
        currentMonth.value === 11 ? currentYear.value + 1 : currentYear.value;

    let nextMonthDay = 1;
    for (let i = days.length; i < totalCells; i++) {
        const dateStr = formatDate(nextYear, nextMonth, nextMonthDay);
        const institutions = getInstitutionsForDate(dateStr);

        days.push({
            date: nextMonthDay,
            isCurrentMonth: false,
            isToday: isToday(nextYear, nextMonth, nextMonthDay),
            institutions: institutions,
        });
        nextMonthDay++;
    }

    return days;
});

function getInstitutionsForDate(dateStr) {
    const institutions = new Set();

    // Convert dateStr (YYYY-MM-DD) to readable format
    const readableDate = formatDateToReadable(dateStr);

    // Check schedulesPerHEI for matching dates
    props.schedulesPerHEI.forEach((schedule) => {
        if (schedule.monitoringDate === readableDate) {
            institutions.add(schedule.institution);
        }
    });

    // Check schedulesPerProgram for matching dates
    props.schedulesPerProgram.data.forEach((schedule) => {
        if (schedule.monitoringDate === readableDate) {
            institutions.add(schedule.institution);
        }
    });

    return Array.from(institutions);
}

function formatDateToReadable(dateStr) {
    // Convert "2025-06-05" to "Jun 05, 2025" (with leading zero)
    const date = new Date(dateStr);
    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec",
    ];

    const month = months[date.getMonth()];
    const day = String(date.getDate()).padStart(2, "0"); // Add leading zero
    const year = date.getFullYear();

    return `${month} ${day}, ${year}`;
}
</script>
