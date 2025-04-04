<template>
    <Head title="Dashboard" />
    <page-title title="Dashboard">
        <template #buttons>
            <options  @filter="toggleFilterModal" href="/ched/dashboard" />
        </template>
    </page-title>
    <div class="mx-3 md:mx-8 mt-2 w-fit" v-if="showFiltersApplied && (supervisor_name != null || heiName != null || programName != null)">
        <div class="flex justify-between items-center bg-gray-100 p-3 rounded-lg text-gray-600">
            <div class="text-sm">
                <span>Filters Applied: </span>
                <span v-if="supervisor_name" class="text-blue-400">ES: {{ supervisor_name }}</span>
                <span v-if="supervisor_name && (heiName || programName)" class="text-blue-400">, </span>
                <span v-if="heiName" class="text-blue-400">HEI: {{ heiName }}</span>
                <span v-if="heiName && programName" class="text-blue-400">, </span>
                <span v-if="programName" class="text-blue-400">Program: {{ programName }}</span>
            </div>
            <button @click="showFiltersApplied = false" class="text-gray-500 hover:text-gray-700 ml-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    <p class="mx-3 md:mx-8 mt-5 w-fit text-gray-600 font-medium">Compliance tools for academic year {{ academicyear }}</p>
    <!-- Statistics Cards Grid -->
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mx-3 md:mx-8 rounded-lg mt-2"
    >
        <div
            class="bg-white rounded-lg p-6 border border-gray-300 shadow-md hover:shadow-lg transition-shadow cursor-pointer"
            @click="viewTools(academicyear, 'Submitted')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ readyforvisit || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2 text-sm">Ready for visit</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg text-blue-500">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="3" width="16" height="18" rx="2" ry="2"/>
                        <path d="M9 3h6M9 14l2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg p-6 border border-gray-300 shadow-md hover:shadow-lg transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'In progress')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-green-500">
                        {{ inprogress || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2 text-sm">In Progress</p>
                </div>
                <div class="p-2 bg-green-100 rounded-lg text-green-500">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" stroke-opacity="0.3"/>
                        <path d="M12 2 A10 10 0 0 1 22 12" stroke="currentColor"/>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg p-6 border border-gray-300 shadow-md hover:shadow-lg transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'For re-evaluation')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-500">
                        {{ forreevaluation || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2 text-sm">For Re-evaluation</p>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M23 4v6h-6M1 20v-6h6"/>
                        <path d="M3.51 9a9 9 0 0114.53-3.36L23 10M20.49 15a9 9 0 01-14.53 3.36L1 14"/>
                    </svg>
                </div>
            </div>
        </div>

        <div
            class="bg-white rounded-lg p-6 border border-gray-300 shadow-md hover:shadow-lg transition-shadow cursor-pointer"
            @click="viewTools(academicyear,'Monitored')"
        >
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-purple-500">
                        {{ monitored || 0 }}
                    </p>
                    <p class="text-gray-600 mt-2 text-sm">Monitored</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-lg text-purple-500">
                    <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3v18h18"/>
                        <path d="M6 16l6-6 4 4 5-5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <p class="mx-3 md:mx-8 mt-10 text-lg w-fit text-gray-600 font-bold">Programs Monitored {{ filters.year }}</p>

    
    <div class="max-w-full mx-auto px-4 md:px-3 lg:px-8 py-5">
        <div
            class="rounded-xl shadow overflow-hidden"
        >
            <div class="flex items-center p-5 border border-gray-200 rounded-t-xl bg-white">
                <h2 class="text-lg font-semibold text-gray-700">Monthly Overview</h2>
            </div>  

            <!-- Main Content -->
            <div class="p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    <!-- Monthly Average -->
                    <div
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                    <div v-show="filters.year === currentYear"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
                        @click="titleStatus = 'Current Month'; viewMonitoredTools(filters.year, monthIndex+1, null)"
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
                    <div class="border border-gray-300 p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">
                            Monthly Distribution
                        </h3>
                        <div v-if="evaluatedTotal == 0" class="py-32 w-full text-center text-gray-500">
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
                            @barClick="handleBarClick"
                        />
                        <!-- <line-chart v-else :lineChartDataItem="[12, 23, 23, 41]" :labels="['a', 'b', 'c', 'd']" /> -->
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="max-w-full mx-auto px-4 md:px-3 lg:px-8 py-8">
        <div
            class="rounded-xl shadow overflow-hidden"
        >
            <div class="flex items-center p-5 border border-gray-200 rounded-t-xl bg-white">
                <h2 class="text-lg font-semibold text-gray-700">Quarterly Overview</h2>
            </div>  

            <!-- Main Content -->
            <div class="p-8">

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <!-- Q1 -->
                    <div @click="titleStatus = '1st Quarter'; viewMonitoredTools(filters.year, null, 1);"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-500">
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
                    <div @click="titleStatus = '2nd Quarter'; viewMonitoredTools(filters.year, null, 2);"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                            <div class="p-2 bg-green-100 rounded-lg text-green-500">
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
                    <div @click="titleStatus = '3th Quarter'; viewMonitoredTools(filters.year, null, 3);"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                            <div class="p-2 bg-orange-100 rounded-lg text-orange-500">
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
                    <div @click="titleStatus='4th Quarter'; viewMonitoredTools(filters.year, null, 4,);"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                            <div class="p-2 bg-violet-100 rounded-lg text-violet-500">
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
                <div class="grid grid-cols-1 gap-8 mt-8">
                    <!-- Chart -->
                    <div class="border border-gray-300 p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">
                            Quarterly Distribution
                        </h3>
                        <half-doughnut-chart v-if="quarterData && quarterData.some(data => data !== 0)" :dataItem="quarterData" :color="['#60A5FA', '#4ADE80', '#FB923C', '#A78BFA']"
                        :labels="['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter']"
                        >
                        </half-doughnut-chart>
                        <div v-else class="py-32 w-full text-center text-gray-500">
                            No data to display
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- <div class="max-w-full mx-auto px-4 md:px-3 lg:px-8 py-8">
        <div
            class="rounded-xl shadow overflow-hidden"
        >
            <div class="flex items-center p-5 border border-gray-200 rounded-t-xl bg-white">
                <h2 class="text-lg font-semibold text-gray-700">Annual Overview</h2>
            </div>  

            
            <div class="p-8">

                
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                    
                    <div
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
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
                                    
                                    <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                                    <line x1="7" y1="2" x2="7" y2="6" stroke="currentColor"/>
                                    <line x1="17" y1="2" x2="17" y2="6" stroke="currentColor"/>
                                    
                                    
                                    <line x1="7" y1="14" x2="7" y2="18" stroke="currentColor"/>
                                    <line x1="12" y1="12" x2="12" y2="18" stroke="currentColor"/>
                                    <line x1="17" y1="16" x2="17" y2="18" stroke="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    
                    <div v-show="filters.year === currentYear"
                        class="p-6 rounded-xl shadow-md border border-gray-300 transition-all duration-300 hover:shadow-lg bg-white cursor-pointer"
                        @click="viewMonitoredTools(filters.year, monthIndex, null)"
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
                                    
                                    <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor"/>
                                    <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                                    <line x1="7" y1="2" x2="7" y2="6" stroke="currentColor"/>
                                    <line x1="17" y1="2" x2="17" y2="6" stroke="currentColor"/>
                            
                                    
                                    <circle cx="12" cy="15" r="2" fill="currentColor"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 gap-8 mt-8">
                    
                    <div class="border border-gray-300 p-6 rounded-xl shadow-md">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">
                            Annual Distribution
                        </h3>
                        <div v-if="evaluatedTotal == 0" class="py-32 w-full text-center text-gray-500">
                            No data to display
                        </div>
                        <analytics-line-chart v-if="displayAnalyticsData" :thisYear="props.thisYear" :lastYear="props.lastYear" :twoYearsAgo="props.twoYearsAgo" :labels="analyticsLabel" :isCurrentYear="isCurrentYear" :currentQuarter="currentQuarter"></analytics-line-chart>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <modal
        :showModal="showFilterModal"
        title="Filters"
        @close="toggleFilterModal"
        width="lg"
        class="antialiased"
    >
        <div class="space-y-6">
            <div class="flex flex-col space-y-2" 
            v-if="
                $page.props.auth.user.role != 'Education Supervisor'
            ">
                <label for="es" class="text-sm font-medium text-gray-700">
                    Education Supervisor
                </label>
                <select
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
                    Compliance Tool Academic Year
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

            <div class="flex flex-col space-y-2">
                <label for="program" class="text-sm font-medium text-gray-700">
                    Monitored Programs by Year
                </label>
                <select
                    v-model="query.year"
                    id="program"
                    class="block w-full px-3 py-2 pr-10 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                    <option value="2028">2028</option>
                    <option value="2029">2029</option>
                    <option value="2030">2030</option>
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
        :showModal="viewToolModal"
        :title="titleStatus + ' ' + filters.year"
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
                    >Program/Institution</th>
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
                        class="hover:bg-blue-200 border-b border-gray-200"
                        :class="{ 'bg-slate-200': index % 2 === 1 }"
                    >
                        <td class="px-6 py-2 text-gray-900 align-top">
                            <div>
                                {{ tool.institution_program.program.program }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ tool.institution_program.institution.name }}
                            </div>
                        </td>
                        <td class="px-6 py-2 align-top whitespace-nowrap text-right">
                            <div class="flex items-center gap-0 text-right">
                                <a :href="route('evaluation.ched.view', tool.id)" target="_blank" rel="noopener noreferrer">
                                    <button class="text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        View tool
                                    </button>
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
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import axios from "axios";
import Layout from "@/Shared/Layout.vue";
defineOptions({ layout: Layout });

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
    "supervisor_name",
    "supervisor_list",
]);

const showFilterModal = ref(false);
const showPeriodModal = ref(false);
const showFiltersApplied = ref(true);
const viewToolModal = ref(false);
const selectedTools = ref({});
const fetchingData = ref(false);
const titleStatus = ref("");

const viewTools = async (academicyear, status) => {
  viewToolModal.value = true;
  fetchingData.value = true;
  titleStatus.value = status == 'Submitted' ? 'Ready for visit' : status ;
    
  try {
    const response = await axios.get(`/api/dashboard/tool/${academicyear}/${status}/${props.supervisor}/${props.program}/${props.hei}/view`);
    if (!response.data) {
      console.error("No data received from API");
      return;
    }
      selectedTools.value = response.data;
      fetchingData.value = false;
      console.log(selectedTools.value.length);
      
  } catch (error) {
    console.error("Error fetching data:", error.response?.data || error.message);
  } finally {
    fetchingData.value = false;
  }
};

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



function handleBarClick(monthIndex) {
    titleStatus.value = months[monthIndex-1];
    viewMonitoredTools(props.filters.year, monthIndex, null);
}


const viewMonitoredTools = async (year, month, quarter) => {
  viewToolModal.value = true;
  fetchingData.value = true;
  
  try {
    const response = await axios.get(`/api/dashboard/tool/${props.supervisor}/${props.program}/${props.hei}/${year}/${month}/${quarter}/view`);
    if (!response.data) {
      console.error("No data received from API");
      return;
    }
      selectedTools.value = response.data;
      fetchingData.value = false;
      console.log(selectedTools.value.length);
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
