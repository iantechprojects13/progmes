<template>

    <Head title="Test User Login" />
    <div class="flex items-center justify-center h-screen">
        <div class="max-w-md mx-auto">
            <div class="mb-3 font-bold">Test User</div>
            <select v-model="login.user" id="usersDropDown" class="w-full">
                <option :value="null">Select user</option>
                <option v-for="user in users" :key="user.id" :value="user.id">
                    <span>{{ user.name }}</span>
                </option>
            </select>
            <button @click="userLogin" class="w-full h-10 bg-gray-700 hover:bg-gray-900 text-white mt-3">
                LOGIN
            </button>
        </div>
    </div>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { reactive } from 'vue';

    const props = defineProps([
        'users',
    ]);

    const login = reactive({
        user: null,
    })

    function userLogin() {
        router.post('/testuserlogin', login);
    }

</script>