<template>
    <Head title="Dashboard" />
    <page-title title="Dashboard" />
    <div
        class="mx-3 md:mx-8 bg-white rounded-lg shadow-sm shadow-gray-200 p-6 mb-8"
    >
        <div
            class="flex flex-col lg:flex-row items-center justify-between gap-6"
        >
            <div class="w-full lg:w-1/2">
                <div
                    v-if="!hei && !program"
                    class="flex items-center text-gray-700"
                >
                    <i class="fas fa-book text-blue-600 mr-3"></i>
                    <span class="font-medium">Overall</span>
                </div>

                <div v-if="heiName" class="flex items-center text-gray-700">
                    <i class="fas fa-institution text-blue-600 mr-3"></i>
                    <span class="font-medium">{{ heiName }}</span>
                </div>

                <div v-if="programName" class="flex items-center text-gray-700">
                    <i class="fas fa-book text-blue-600 mr-3"></i>
                    <span class="font-medium">
                        {{ programName }}
                        <span v-if="major" class="text-gray-500"
                            >- {{ major }}</span
                        >
                    </span>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex gap-2">
                <button
                    @click="toggleFilterModal"
                    class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                    data-tooltip="Filters"
                >
                    <svg
                        class="w-6 h-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        />
                    </svg>
                    <span
                        class="absolute -top-8 left-1/2 -translate-x-1/2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                    >
                        Filters
                    </span>
                </button>

                <Link href="/ched/dashboard">
                    <button
                        class="p-2.5 inline-flex items-center justify-center rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 tooltipForActions"
                        data-tooltip="Refresh page"
                    >
                        <svg
                            class="w-6 h-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>
                    </button>
                </Link>
            </div>
        </div>
    </div>


    <!-- Statistics Cards Grid -->
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mx-3 md:mx-8 mt-8 border border-gray-200 rounded-lg p-3 py-5 bg-blue-50"
    >
        <div
            class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'Submitted')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ readyforvisit || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2">Ready for visit</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-clipboard-check text-blue-500 text-xl"></i>
                </div>
                <!-- <div class="mt-4 flex items-center text-sm text-green-500">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>8% increase</span>
                </div> -->
            </div>
        </div>

        <div
            class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'In progress')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-green-600">
                        {{ inprogress || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2">In Progress</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-spinner text-green-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'For re-evaluation')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-600">
                        {{ forreevaluation || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2">For Re-evaluation</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <i class="fas fa-redo text-orange-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'Monitored')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ monitored || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2">Monitored</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>


    <div class="mx-3 md:mx-8 px-4 py-6 flex md:flex-row flex-col items-center justify-between bg-white shadow-sm shadow-gray-200 rounded-xl mt-12">
        <div class="flex items-center gap-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                    d="M8 13v-1m4 1v-3m4 3V8M12 21a9 9 0 100-18 9 9 0 000 18z" />
                </svg>
            <h2 class="text-base md:text-xl font-bold text-gray-700">
                Programs Monitored {{ filters.year }}
            </h2>
        </div>
        <div class="flex items-center gap-2">
            <select v-model="query.year" class="px-4 py-2 pr-10 rounded-lg border bg-white" @change="applyFilters">
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <option value="2022">2022</option>
                <option value="2021">2021</option>
                <option value="2020">2020</option>
            </select>
        </div>
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-3 lg:px-8 py-8">
        <div
            class="bg-blue-50 rounded-xl shadow overflow-hidden"
        >
            <div class="flex items-center p-5 border border-gray-200 rounded-t-xl bg-white">
                <h2 class="text-xl font-bold text-gray-700">Quarterly Overview</h2>
            </div>  

            <!-- Main Content -->
            <div class="p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Q1 -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ quarterData[0] }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    1st Quarter
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Pie Chart (Quarter) -->
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                    <path d="M12 12 L12 2 A10 10 0 0 1 22 12 Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ quarterData[1] }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    2nd Quarter
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Pie Chart (Quarter) -->
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                    <path d="M12 12 L22 12 A10 10 0 0 1 12 22 Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ quarterData[2] }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    3rd Quarter
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Pie Chart (Quarter) -->
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                    <path d="M12 12 L12 22 A10 10 0 0 1 2 12 Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ quarterData[3] }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    4th Quarter
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Pie Chart (Quarter) -->
                                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                    <path d="M12 12 L2 12 A10 10 0 0 1 12 2 Z" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart and Summary Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                    <!-- Chart -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">
                            Quarterly Distribution
                        </h3>
                        <div
                            class="h-64 bg-gray-50 rounded-lg flex items-center justify-center"
                        >
                        <div class="flex items-center text-center w-full mt-8 lg:mt-0">
                            <compliance-status v-if="quarterData && quarterData.some(data => data !== 0)" :dataItem="quarterData" :color="['#03d', '#07f', '#09f', '#0cf']"
                            :labels="['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter']"
                            >
                            </compliance-status>
                            <div v-else class="w-full text-gray-500">
                                No data to display
                            </div>
                        </div>
                        </div>
                    </div>

                    <!-- Summary Stats -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">
                            Performance Overview
                        </h3>
                        <div class="space-y-4">
                            <div
                                class="flex justify-between items-center p-4 bg-gray-50 rounded-lg"
                            >
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Total Programs
                                    </p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        {{ evaluatedTotal }}
                                    </p>
                                </div>
                                <svg
                                    class="w-8 h-8 text-blue-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                            </div>

                            <div
                                class="flex justify-between items-center p-4 bg-gray-50 rounded-lg"
                            >
                                <div>
                                    <p class="text-sm text-gray-600">
                                        Monitoring Performance
                                    </p>
                                    <p v-html="monitoredPercentage($page.props.auth.user.role, monitored, totalPrograms, filters.year, isCurrentYear)">

                                    </p>
                                </div>
                                <svg
                                    class="w-8 h-8 text-green-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-3 lg:px-8 py-8">
        <div
            class="bg-blue-50 rounded-xl shadow overflow-hidden"
        >
            <div class="flex items-center p-5 border border-gray-200 rounded-t-xl bg-white">
                <h2 class="text-xl font-bold text-gray-700">Monthly Overview</h2>
            </div>  

            <!-- Main Content -->
            <div class="p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Monthly Average -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ monthlyAverageResult() }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    Monthly Average
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Calendar Shape -->
                                    <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                                    <line x1="7" y1="2" x2="7" y2="6" stroke="currentColor"/>
                                    <line x1="17" y1="2" x2="17" y2="6" stroke="currentColor"/>
                                    
                                    <!-- Bar Chart Representation -->
                                    <line x1="7" y1="14" x2="7" y2="18" stroke="currentColor"/>
                                    <line x1="12" y1="12" x2="12" y2="18" stroke="currentColor"/>
                                    <line x1="17" y1="16" x2="17" y2="18" stroke="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Current Month -->
                    <div
                        class="p-6 rounded-xl shadow-sm border border-gray-100 transition-all duration-300 hover:shadow-md bg-white"
                    >
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-2xl font-bold text-gray-800"
                                    >{{ byMonthData[monthIndex] }}</span
                                >
                                <p
                                    class="text-sm font-medium text-gray-500 mt-1"
                                >
                                    Current Month
                                </p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg
                                    class="w-8 h-8"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <!-- Calendar Shape -->
                                    <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                                    <line x1="7" y1="2" x2="7" y2="6" stroke="currentColor"/>
                                    <line x1="17" y1="2" x2="17" y2="6" stroke="currentColor"/>
                            
                                    <!-- Highlighted Date (Centered Circle) -->
                                    <circle cx="12" cy="15" r="2" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart and Summary Section -->
                <div class="grid grid-cols-1 gap-8 mt-8">
                    <!-- Chart -->
                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">
                            Monthly Distribution
                        </h3>
                        <div v-if="evaluatedTotal == 0" class="py-10 w-full text-center text-gray-500">
                            No data to display
                        </div>
                        <bar-chart
                            v-else
                            :dataItem="byMonthData"
                            :color="['#0af']"
                            :labels="[
                                'Jan',
                                'Feb',
                                'Mar',
                                'Apr',
                                'May',
                                'Jun',
                                'Jul',
                                'Aug',
                                'Sep',
                                'Oct',
                                'Nov',
                                'Dec',
                            ]"
                        />
                        <!-- <line-chart v-else :lineChartDataItem="[12, 23, 23, 41]" :labels="['a', 'b', 'c', 'd']" /> -->
                    </div>

                </div>
            </div>
        </div>
    </div>


    <modal
        :showModal="showFilterModal"
        title="Filters"
        @close="toggleFilterModal"
        width="lg"
        class="antialiased"
    >
        <div class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label for="es" class="text-sm font-medium text-gray-700">
                    Education Supervisor
                </label>
                <select
                    v-show="
                        $page.props.auth.user.role != 'Education Supervisor'
                    "
                    v-model="query.supervisor"
                    id="es"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="es in supervisor_list"
                        :key="es.id"
                        :value="es.id"
                    >
                        {{ es.name }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="hei" class="text-sm font-medium text-gray-700">
                    HEI
                </label>
                <select
                    v-model="query.hei"
                    id="hei"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="hei in hei_list"
                        :key="hei.id"
                        :value="hei.id"
                    >
                        {{ hei.name }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="program" class="text-sm font-medium text-gray-700">
                    Program
                </label>
                <select
                    v-model="query.program"
                    id="program"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="program in program_list"
                        :key="program.id"
                        :value="program.id"
                    >
                        {{ program.program }}
                        <span v-if="program.major">- {{ program.major }}</span>
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="program" class="text-sm font-medium text-gray-700">
                    Academic Year
                </label>
                <select
                    v-model="query.academicyear"
                    id="program"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="2023-2024">A.Y. 2023-24</option>
                    <option value="2024-2025">A.Y. 2024-25</option>
                    <option value="2025-2026">A.Y. 2025-26</option>
                    <option value="2026-2027">A.Y. 2026-27</option>
                    <option value="2027-2028">A.Y. 2027-28</option>
                    <option value="2028-2029">A.Y. 2028-29</option>
                    <option value="2029-2030">A.Y. 2029-30</option>
                </select>
            </div>
        </div>

        <template #custom-button>
            <div class="flex justify-end space-x-2">
                <button
                    @click="applyFilters"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-lg shadow-sm hover:bg-blue-600"
                >
                    Apply
                </button>
            </div>
        </template>
    </modal>

    <modal
        :showModal="showPeriodModal"
        title="Filters"
        @close="togglePeriodModal"
        width="lg"
        class="antialiased"
    >
        <div class="space-y-6">
            <div class="flex flex-col space-y-2">
                <label for="es" class="text-sm font-medium text-gray-700">
                    Education Supervisor
                </label>
                <select
                    v-show="
                        $page.props.auth.user.role != 'Education Supervisor'
                    "
                    v-model="query.supervisor"
                    id="es"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="es in supervisor_list"
                        :key="es.id"
                        :value="es.id"
                    >
                        {{ es.name }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="hei" class="text-sm font-medium text-gray-700">
                    HEI
                </label>
                <select
                    v-model="query.hei"
                    id="hei"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="hei in hei_list"
                        :key="hei.id"
                        :value="hei.id"
                    >
                        {{ hei.name }}
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="program" class="text-sm font-medium text-gray-700">
                    Program
                </label>
                <select
                    v-model="query.program"
                    id="program"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option :value="null">All</option>
                    <option
                        v-for="program in program_list"
                        :key="program.id"
                        :value="program.id"
                    >
                        {{ program.program }}
                        <span v-if="program.major">- {{ program.major }}</span>
                    </option>
                </select>
            </div>
            <div class="flex flex-col space-y-2">
                <label for="program" class="text-sm font-medium text-gray-700">
                    Academic Year
                </label>
                <select
                    v-model="query.academicyear"
                    id="program"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="2023-2024">A.Y. 2023-24</option>
                    <option value="2024-2025">A.Y. 2024-25</option>
                    <option value="2025-2026">A.Y. 2025-26</option>
                    <option value="2026-2027">A.Y. 2026-27</option>
                    <option value="2027-2028">A.Y. 2027-28</option>
                    <option value="2028-2029">A.Y. 2028-29</option>
                    <option value="2029-2030">A.Y. 2029-30</option>
                </select>
            </div>
        </div>

        <template #custom-button>
            <button
                @click="applyFilters"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
            >
                Apply
            </button>
        </template>
    </modal>

    

    <modal
        :showModal="viewToolModal"
        title="Filters"
        @close="viewToolModal = false"
        width="4xl"
        class="antialiased"
        hasNoCancelButton="true"
    >
        <div class="h-52 flex items-center justify-center" v-if="fetchingData">
            <svg class="h-12 w-12 text-blue-500 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 20v2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M4.93 4.93l1.41 1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M17.66 17.66l1.41 1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M2 12h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M20 12h2" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M4.93 19.07l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <path d="M17.66 6.34l1.41-1.41" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>           
        </div>
        <div class="space-y-6 max-h-96 overflow-y-auto" v-else>
            <content-table>
                <template v-slot:table-head>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >HEI</th>
                    <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >Program</th>
                    <!-- <th
                        class="px-6 py-4 text-left text-base font-bold text-gray-800"
                    >Progress</th> -->
                    <th class="px-6 py-4 text-right w-24">
                        <span class="text-gray-800">
                            <i class="fas fa-ellipsis-v text-lg"></i>
                        </span>
                    </th>
                </template>
                <template v-slot:table-body>
                    <tr v-if="selectedTools.length == 0">
                        <no-search-result text="tools" />
                    </tr>
                    <tr
                        v-else
                        v-for="(tool, index) in selectedTools"
                        :key="tool.id"
                        class="transition-colors hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ tool.institution_program.institution.name }}
                        </td>
                        <td class="px-6 py-2 text-gray-900 align-top">
                            {{ tool.institution_program.program.program }}
                        </td>
                        <td class="px-6 py-2 align-top whitespace-nowrap">
                            <div class="flex items-center gap-0">
                                <a :href="route('evaluation.ched.view', tool.id)" target="_blank" rel="noopener noreferrer" class="bg-blue-500 p-2 py-1 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                </template>
            </content-table>
        </div>

        <template #custom-button>
            <button
            @click="viewToolModal = false"
            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
          >
            Close
          </button>
        </template>
    </modal>

</template>

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import axios from "axios";

const props = defineProps([
    "complianceTool",
    "filters",
    "userCount",
    "institutionCount",
    "programCount",
    "disciplineCount",
    "usertype",
    "thisYear",
    "lastYear",
    "twoYearsAgo",
    "currentYear",
    "academicyear",
    "readyforvisit",
    "inprogress",
    "forreevaluation",
    "monitored",
    "program_list",
    "program",
    "major",
    "hei_list",
    "hei",
    "heiName",
    "programName",
    "quarterData",
    "evaluatedTotal",
    "byMonthData",
    "monthIndex",
    "isCurrentYear",
    "analyticsLabel",
    "displayAnalyticsData",
    "totalPrograms",
    "currentQuarter",
    "supervisor",
    "supervisor_list",
]);

const showFilterModal = ref(false);
const showPeriodModal = ref(false);

const viewToolModal = ref(false);
const selectedTools = ref({});
const fetchingData = ref(false);


const viewTools = async (academicyear, status) => {
  viewToolModal.value = true;
  fetchingData.value = true;

  try {
    const response = await axios.get(`/api/dashboard/tool/${academicyear}/${status}/view`);
    if (!response.data) {
      console.error("No data received from API");
      return;
    }
      selectedTools.value = response.data;
      fetchingData.value = false;
  } catch (error) {
    console.error("Error fetching institution:", error.response?.data || error.message);
  } finally {
    fetchingData.value = false;
  }
};

function toggleFilterModal() {
    showFilterModal.value = !showFilterModal.value;
}

function togglePeriodModal() {
    showPeriodModal.value = !showPeriodModal.value;
}

const query = useForm({
    supervisor: ref(props.supervisor) != null ? ref(props.supervisor) : null,
    hei: ref(props.hei) != null ? ref(props.hei) : null,
    program: ref(props.program) != null ? ref(props.program) : null,
    year: ref(props.filters.year) != null ? ref(props.filters.year) : null,
    academicyear:
        ref(props.filters.academicyear) != null
            ? ref(props.filters.academicyear)
            : null,
    period:
        ref(props.filters.period) != null ? ref(props.filters.period) : null,
});


function applyFilters() {
    query.get("/ched/dashboard", {
        preserveState: false,
        preserveScroll: true,
        replace: true,
    });
}

//============================================================================
// Monthly

const monthlyAverageResult = () => {
    let total = 0;
    let monthly_average = 0;
    for (var i = 0; i < props.monthIndex; i++) {
        total = total + props.byMonthData[i];
    }
    monthly_average = total / props.monthIndex;
    return monthly_average.toFixed(0);
};

//===========================================================================
// Quarterly

const byQuarterData = (quarterData, quarter) => {
    let arr = [];
    let programName = props.programName != null ? props.programName : "";
    let percentage = (quarterData / props.evaluatedTotal) * 100;
    let heiName = props.heiName != null ? props.heiName : "";

    arr.push(quarter);
    arr.push(props.filters.year);
    arr.push(quarterData);
    arr.push(props.evaluatedTotal);
    arr.push(percentage.toFixed(2));
    arr.push(heiName);
    arr.push(programName);
    arr.push(byQuarterPercentage(quarterData));
    arr.push(props.totalPrograms);
    return arr;
};

const byQuarterPercentage = (quarterData) => {
    let percentage = 0;

    if (quarterData) {
        percentage = (quarterData / props.totalPrograms) * 100;
        return percentage.toFixed(2);
    } else {
        percentage = null;
        return percentage;
    }
};

const performance = () => {
    let percentage = 0;

    percentage = (props.evaluatedTotal / props.totalPrograms) * 100;

    // return percentage.toFixed(2);

    if (percentage <= 20) {
        return "Very Low";
    } else if (percentage > 20 && percentage <= 40) {
        return "Low";
    } else if (percentage > 40 && percentage <= 60) {
        return "Moderate";
    } else if (percentage > 60 && percentage <= 80) {
        return "High";
    } else if (percentage > 80) {
        return "Very High";
    } else {
        return "N/A";
    }
};

const monitoredPercentage = (role) => {
    let progOrHei = props.programName
        ? " in " + props.programName
        : props.heiName
        ? " in " + props.heiName
        : "";
    let percentage = 0;
    let performance = "";
    let advice = "";
    let description = "";
    let currentQuarter =
        props.currentQuarter == 1
            ? "Q1"
            : props.currentQuarter == 2
            ? "Q2"
            : props.currentQuarter == 3
            ? "Q3"
            : props.currentQuarter == 4
            ? "Q4"
            : null;
    let threshold =
        props.currentQuarter == 1
            ? 25
            : props.currentQuarter == 2
            ? 50
            : props.currentQuarter == 3
            ? 75
            : props.currentQuarter == 4
            ? 100
            : null;
    let targetThreshold = 0;
    let thresholdPercentage = threshold / 100;
    let overallPercentage = 0;

    percentage = (props.evaluatedTotal / props.totalPrograms) * 100;

    if (props.isCurrentYear) {
        percentage = (percentage / threshold) * 100;

        targetThreshold = props.totalPrograms * thresholdPercentage;
        overallPercentage = (
            (props.evaluatedTotal / props.totalPrograms) *
            100
        ).toFixed(2);
    }

    let percentageFloat = parseFloat(percentage);
    let excess = percentageFloat - 100;

    if (!props.isCurrentYear) {
        if (percentage <= 20) {
            performance =
                "This indicates <b class='text-red-500'>very low monitoring performance</b>, which means that only a small fraction of programs are monitored. ";
        } else if (percentage > 20 && percentage <= 40) {
            performance =
                "This indicates <b class='text-red-500'>low monitoring performance</b>, which means that less than half of the programs" +
                progOrHei +
                " are monitored. ";
        } else if (percentage > 40 && percentage <= 60) {
            performance =
                "This indicates <b class='text-green-600'>moderate monitoring performance</b>, which means that about half of the programs are monitored. ";
        } else if (percentage > 60 && percentage <= 80) {
            performance =
                "This indicates <b class='text-blue-500'>high monitoring performance</b>, which means that the majority of programs are monitored. ";
        } else if (percentage > 80 && percentage <= 100) {
            performance =
                "This indicates <b class='text-blue-500'>very high monitoring performance</b>, which means that (nearly) all programs are monitored. ";
        } else if (percentage > 100) {
            performance =
                "This <b class='text-blue-500'>very high monitoring performance</b> indicates that not only were all programs monitored" +
                progOrHei +
                ", but there was also an additional " +
                excess.toFixed(2) +
                "% of coverage beyond the total number of programs. ";
        }
    } else {
        if (percentage <= 20) {
            performance =
                "This indicates <b class='text-red-500'>very low monitoring performance</b>, which means that only a small fraction of programs are monitored. ";
            advice =
                "Immediate intervention is needed to significantly increase the monitoring rate and address the large gap in performance.";
        } else if (percentage > 20 && percentage <= 40) {
            performance =
                "This indicates <b class='text-red-500'>low monitoring performance</b>, which means that less than half of the programs" +
                progOrHei +
                " are monitored. ";
            advice =
                "Immediate action is necessary to improve the monitoring rate and bring it closer to the expected threshold.";
        } else if (percentage > 40 && percentage <= 60) {
            performance =
                "This indicates <b class='text-green-600'>moderate monitoring performance</b>, which means that about half of the programs are monitored. ";
            advice = "Continued effort is required to reach the target.";
        } else if (percentage > 60 && percentage <= 80) {
            performance =
                "This indicates <b class='text-blue-500'>high monitoring performance</b>, which means that the majority of programs are monitored. ";
            advice =
                "Continued effort is essential to maintain momentum and meet the full target.";
        } else if (percentage > 80 && percentage <= 100) {
            performance =
                "This indicates <b class='text-blue-500'>very high monitoring performance</b>, which means that (nearly) all programs are monitored. ";
            advice =
                "Maintain the current level of performance with continued diligence.";
        } else if (percentage > 100) {
            performance =
                "This <b class='text-blue-500'>very high performance</b> indicates that not only were all programs monitored" +
                progOrHei +
                ", but there was also an additional " +
                excess.toFixed(2) +
                "% of coverage beyond the total number of programs. ";
            advice =
                "Maintain the current level of performance with continued diligence.";
        }
    }

    if (props.program) {
        if (props.isCurrentYear) {
            description =
                "As of " +
                currentQuarter +
                " " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " programs were monitored " +
                progOrHei +
                ", which is " +
                percentage.toFixed(2) +
                "% of the target threshold (" +
                targetThreshold.toFixed(0) +
                " programs) for " +
                currentQuarter +
                ". " +
                performance +
                advice;
        } else {
            description =
                "In " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " " +
                props.programName +
                " programs were monitored, which is " +
                percentage.toFixed(2) +
                "% of the " +
                props.totalPrograms +
                " programs available. " +
                performance;
        }
    } else if (props.hei) {
        if (props.isCurrentYear) {
            description =
                "As of " +
                currentQuarter +
                " " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " programs were monitored " +
                progOrHei +
                ", which is " +
                percentage.toFixed(2) +
                "% of the target threshold (" +
                targetThreshold.toFixed(0) +
                " programs) for " +
                currentQuarter +
                ". " +
                performance +
                advice;
        } else {
            description =
                "In " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " programs in the " +
                props.heiName +
                " were monitored, which is " +
                percentage.toFixed(2) +
                "% of the " +
                props.totalPrograms +
                " programs available. " +
                performance;
        }
    } else {
        if (props.isCurrentYear) {
            description =
                "As of " +
                currentQuarter +
                " " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " programs were monitored, which is " +
                percentage.toFixed(2) +
                "% of the target threshold (" +
                targetThreshold.toFixed(0) +
                " programs) for " +
                currentQuarter +
                ". " +
                performance +
                advice;
        } else {
            description =
                "In " +
                props.filters.year +
                ", " +
                props.evaluatedTotal +
                " programs were monitored, which is " +
                percentage.toFixed(2) +
                "% of the " +
                props.totalPrograms +
                " programs available. " +
                performance;
        }
    }

    return description;
};

//============================================================================================================
// Analytics (Line Graph)

const getQuarterlyGrowth = (index, data) => {
    if (index == 0 || data[index] == 0 || data[index - 1] == 0) {
        return 0;
    }

    let current = data[index];
    let previous = data[index - 1];

    let rate = ((current - previous) / previous) * 100;

    return rate.toFixed(2);
};

// Reactive Data: Cumulative totals and quarterly growth for each year
const cumulativeTotals = ref({
    twoYearsAgo:
        props.twoYearsAgo[0] +
        props.twoYearsAgo[1] +
        props.twoYearsAgo[2] +
        props.twoYearsAgo[3],
    lastYear:
        props.lastYear[0] +
        props.lastYear[1] +
        props.lastYear[2] +
        props.lastYear[3],
    thisYear:
        props.thisYear[0] +
        props.thisYear[1] +
        props.thisYear[2] +
        props.thisYear[3],
});

const quarterlyGrowth = ref({
    twoYearsAgo: [
        0,
        parseFloat(getQuarterlyGrowth(1, props.twoYearsAgo)),
        parseFloat(getQuarterlyGrowth(2, props.twoYearsAgo)),
        parseFloat(getQuarterlyGrowth(3, props.twoYearsAgo)),
    ],
    lastYear: [
        0,
        parseFloat(getQuarterlyGrowth(1, props.lastYear)),
        parseFloat(getQuarterlyGrowth(2, props.lastYear)),
        parseFloat(getQuarterlyGrowth(3, props.lastYear)),
    ],
    thisYear: [
        0,
        parseFloat(getQuarterlyGrowth(1, props.thisYear)),
        parseFloat(getQuarterlyGrowth(2, props.thisYear)),
        parseFloat(getQuarterlyGrowth(3, props.thisYear)),
    ],
});

// Computed Conclusion based on the data
const conclusion = computed(() => {
    let conclusion = "";

    // Cumulative Totals comparison

    if (
        cumulativeTotals.value["thisYear"] >
            cumulativeTotals.value["lastYear"] &&
        cumulativeTotals.value["thisYear"] >
            cumulativeTotals.value["twoYearsAgo"]
    ) {
        // Highest cumulative growth
        conclusion =
            props.filters.year +
            " has shown the highest overall cumulative growth compared to previous years.";
    } else if (
        cumulativeTotals.value["thisYear"] <
            cumulativeTotals.value["lastYear"] &&
        cumulativeTotals.value["thisYear"] <
            cumulativeTotals.value["twoYearsAgo"]
    ) {
        // Lowest cumulative growth
        conclusion =
            props.filters.year +
            " has the lowest overall cumulative growth compared to previous years, indicating a decline.";
    } else {
        // Moderate cumulative growth
        conclusion =
            props.filters.year +
            "'s cumulative growth is moderate compared to previous years.";
    }

    // New condition: Check for steady or volatile growth

    if (quarterlyGrowth.value["thisYear"].every((q) => q > 0)) {
        // If all quarters have positive growth
        conclusion +=
            " Additionally, the growth in " +
            props.filters.year +
            " was steady, indicating a consistent upward trend.";
    } else if (
        quarterlyGrowth.value["thisYear"].some((q) => q < 0) &&
        quarterlyGrowth.value["thisYear"].some((q) => q > 0)
    ) {
        // Mixed growth (some quarters positive, some negative)
        conclusion +=
            " However, " +
            props.filters.year +
            " experienced a mix of growth and decline, indicating some volatility in program monitoring.";
    } else if (quarterlyGrowth.value["thisYear"].every((q) => q < 0)) {
        // All quarters have negative growth
        conclusion +=
            " Unfortunately, " +
            props.filters.year +
            " faced consistent declines throughout the year.";
    }

    // Additional quarter-specific analysis (e.g., if Q3 was particularly strong or weak)
    if (
        (props.currentQuarter > 2 && props.isCurrentYear) ||
        !props.isCurrentYear
    ) {
        if (
            quarterlyGrowth.value["thisYear"][2] >
            quarterlyGrowth.value["thisYear"][1]
        ) {
            // Q3 stronger than Q2
            conclusion +=
                " The third quarter of " +
                props.filters.year +
                " showed significant improvement compared to the second quarter.";
        } else if (
            quarterlyGrowth.value["thisYear"][2] <
            quarterlyGrowth.value["thisYear"][1]
        ) {
            // Q3 weaker than Q2
            conclusion +=
                " The third quarter of " +
                props.filters.year +
                " saw a decline in performance compared to the second quarter.";
        } else if (
            quarterlyGrowth.value["thisYear"][2] ==
            quarterlyGrowth.value["thisYear"][1]
        ) {
            // Q3 balanced with Q2
            conclusion +=
                " The third quarter of " +
                props.filters.year +
                " remained consistent with the second quarter, showing no significant change in performance.";
        }
    }

    // Check if Q4 shows recovery or decline

    if (
        (props.currentQuarter > 3 && props.isCurrentYear) ||
        !props.isCurrentYear
    ) {
        if (props.thisYear[3] > props.thisYear[4]) {
            conclusion +=
                " However, there was a recovery in the fourth quarter, showing signs of improvement.";
        } else {
            conclusion +=
                " Unfortunately, the fourth quarter also showed signs of decline, suggesting challenges in maintaining growth.";
        }
    }

    return conclusion;
});
</script>

<script>
import Layout from "@/Shared/Layout.vue";

export default {
    layout: Layout,
};
</script>

<style scoped>
.tooltipForScale {
    position: relative;
    cursor: pointer;
}

.tooltipForScale::before {
    content: attr(data-tooltip);
    position: absolute;
    right: 0px;
    bottom: 45px;
    font-size: 13px;
    font-weight: normal;
    width: fit-content;
    height: fit-content;
    white-space: nowrap;
    background-color: #333;
    color: #eee;
    padding: 10px;
    border-radius: 3px;
    display: none;
    z-index: 10000;
}

.tooltipForScale:hover::before {
    display: block;
}
</style>
