<template>
    <Head title="My Account" />
    <page-title title="My Account" />
    <content-container :hideTopBorder="true">
        <template v-slot:main-content>
            <div class="my-8 mx-3 md:mx-5 bg-gray-200 p-6 rounded-lg shadow-lg">
                <div class="flex flex-col items-center">
                    <!-- Profile Avatar -->
                    <img :src="profile.avatar" alt="User Profile" 
                        class="rounded-full border-4 border-gray-300 shadow-lg w-32 h-32">
                    <!-- Name and Email -->
                    <h1 class="text-gray-800 text-2xl mt-3 flex items-center">
                        <i class="fas fa-id-card ml-2 mr-4 text-blue-500"></i> {{ profile.name }}
                    </h1>
                    <p class="text-gray-600 flex items-center">
                        <i class="fas fa-envelope ml-2 mr-4 text-blue-500"></i> {{ profile.email }}
                    </p>
                </div>
                <!-- Account Details -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="profile.type" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-user-tag text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Type</h2>
                            <p class="text-gray-900">{{ profile.type }}</p>
                        </div>
                    </div>
                    <div v-if="profile.role" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-briefcase text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Role</h2>
                            <p class="text-gray-900">{{ profile.role }}</p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.institution" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-university text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Institution</h2>
                            <p class="text-gray-900">{{ roles[0].institution?.name }}</p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.discipline" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-book text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Discipline</h2>
                            <p v-for="role in roles" :key="role.id" class="text-gray-900">
                                {{ role.discipline?.discipline }}
                            </p>
                        </div>
                    </div>
                    <div v-if="roles[0]?.program" class="bg-gray-300 p-4 rounded-lg shadow flex items-center">
                        <i class="fas fa-book text-blue-500 ml-2 mr-4"></i>
                        <div>
                            <h2 class="text-gray-700 font-semibold">Program</h2>
                            <p v-for="role in roles" :key="role.id" class="text-gray-900">
                                {{ role.program?.program }}
                                <span v-if="role.program?.major">
                                    - {{ role.program?.major }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </content-container>
    <pre>
        {{ profile }}
        {{ roles }}
    </pre>
</template>

<script setup>
import { computed } from 'vue';
import Layout from '@/Shared/Layout.vue';

defineOptions({
    layout: Layout,
});

const props = defineProps(['profile', 'roles']);
</script>
