<template>
    <Head title="Application" />
    
    <!-- Hero Section with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-800 p-8">
        <h1 class="text-3xl font-bold text-white mb-2">Program Application</h1>
       
    </div>

    <content-container :hasStickyDiv="true" :hasSearch="true" :hasFilters="true" :hasTopMainContent="true">
        <!-- Action Bar -->
        <template v-slot:content-title>
            <div class="flex justify-end p-4">
                <button @click="update" ref="saveBtn" :disabled="saving"
                    class="flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-lg transition-all duration-200 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </template>

        <!-- Application Details Card -->
        <template v-slot:top-main-content>
            <div class="bg-white rounded-xl shadow-lg p-6 m-4">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Institution Section -->
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <i class="fas fa-university text-2xl text-blue-600 mr-4"></i>
                            <div>
                                <p class="text-sm text-gray-500">Name of HEI</p>
                                <select class="mt-1 w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option>Select Institution</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <i class="fas fa-book-open text-2xl text-blue-600 mr-4"></i>
                            <div>
                                <p class="text-sm text-gray-500">Program Being Applied For</p>
                                <select class="mt-1 w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option>Select Program</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Application Details Section -->
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                            <i class="fas fa-file-alt text-2xl text-blue-600 mr-4"></i>
                            <div>
                                <p class="text-sm text-gray-500">Type of Application</p>
                                <select class="mt-1 w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                    <option>Select Type</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all">
                                <i class="fas fa-calendar text-2xl text-blue-600 mr-4"></i>
                                <div class="w-full">
                                    <p class="text-sm text-gray-500">Academic Year</p>
                                    <select class="mt-1 w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                                        <option>2023-2024</option>
                                        <option>2024-2025</option>
                                        <option>2025-2026</option>
                                    </select>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- Documents Table -->
        <template v-slot:main-content>
            <div class="bg-white rounded-xl shadow-lg m-4 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Documents</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Existing</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Remarks</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Supporting Documents</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">Application Form</div>
                                <div class="text-sm text-gray-500">Required Document</div>
                            </td>
                            <td class="px-6 py-4">
                                    <select class="w-32 px-3 py-2 bg-white border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </td>
                            <td class="px-6 py-4">
                                <textarea class="w-full p-2 border rounded-md focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-file-pdf mr-1"></i> View
                                    </a>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </content-container>
</template>

<style scoped>
.content-container {
    @apply max-w-7xl mx-auto;
}
</style>
<script>
import Layout from '@/Shared/Layout.vue';

export default {
    layout: Layout,
    
        // Your methods here
    }
    </script>