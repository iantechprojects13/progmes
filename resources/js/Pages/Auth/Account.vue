<template>
    <Head title="My Account" />

    <div class="bg-gradient-to-r from-blue-600 to-indigo-800 p-8">
        <h1 class="text-3xl font-bold text-white mb-2">My Account</h1>
    </div>

    <content-container>
        <template v-slot:content-title>
            <div class="flex flex-row items-center justify-between">
                <div class="flex flex-row">
                    <div class="font-bold text">My Account Details</div>
                </div>
            </div>
        </template>

        <template v-slot:main-content>
            <div class="my-8 mx-3 md:mx-5 bg-gray-200 p-6 rounded-lg shadow-lg">
                <div class="flex flex-col items-center">
                    <!-- Profile Avatar -->
                    <img :src="profile.avatar" alt="User Profile" 
                        class="rounded-full border-4 border-gray-300 shadow-lg w-32 h-32">
                    <!-- Name and Email -->
                    <h1 class="text-gray-800 text-2xl font-bold mt-3 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i> {{ profile.name }}
                    </h1>
                    <p class="text-gray-600 flex items-center font-bold">
                        <i class="fas fa-at mr-2 text-blue-500"></i> {{ profile.email }}
                    </p>
                </div>

                <!-- Account Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="profile.type" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-id-badge text-blue-500 mr-2"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Type</h2>
                            <p class="text-gray-900">{{ profile.type }}</p>
                        </div>
                    </div>

                    <div v-if="profile.role" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-user-tie text-blue-500 mr-2"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Role</h2>
                            <p class="text-gray-900">{{ profile.role }}</p>
                        </div>
                    </div>

                    <div v-if="roles.institution" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-building text-blue-500 mr-2"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Institution</h2>
                            <p class="text-gray-900">{{ roles.institution }}</p>
                        </div>
                    </div>

                    <div v-if="roles.discipline" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-microscope text-blue-500 mr-2"></i>
                        <div v-for="role in roles" :key="role.id">
                            <h2 class="text-gray-700 font-semibold">Discipline</h2>
                            <p class="text-gray-900">{{ roles.discipline }}</p>
                        </div>
                    </div>

                    <div v-if="roles[0]?.program?.program" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-user-graduate text-blue-500 mr-2"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Program</h2>
                            <p class="text-gray-900">{{ roles[0].program.program }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
</template>

<script setup>
import { computed } from 'vue';
import Layout from '@/Shared/Layout.vue';

defineOptions({
    layout: Layout,
});

const props = defineProps(['profile', 'roles']);
</script>

<style scoped>
.text-gray-900 {
    font-weight: 600;
}
</style>