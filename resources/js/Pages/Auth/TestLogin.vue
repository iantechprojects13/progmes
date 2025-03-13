<template>

    <Head title="Test User Login" />
	<div class="flex items-center justify-center h-screen">
        <div class="max-w-md mx-auto">
            <div class="mb-3 font-bold">Test User</div>
            <select v-model="login.user" id="usersDropDown" class="w-full">
                <option :value="null">Select user</option>
                <option v-for="user in user_list" :key="user.id" :value="user.id">
                    <span>{{ user.name }}</span>
                </option>
            </select>
            <button @click="testlogin" class="w-full h-10 bg-gray-700 hover:bg-gray-900 text-white mt-3">
                LOGIN
            </button>
        </div>
    </div>
    <div class=" text-start">
        <table class="text-left">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Discipline</th>
                    <th>Program</th>
                    <th>HEI</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in user_list" :key="user.id" class="bg-white border-b border-gray-400" :class="{'bg-gray-900' : index % 2 == 1}">
                    <td class="py-3">{{ user.name }}</td>
                    <td class="py-3">{{ user.role }}</td>
                    <td class="py-3">
                        <div v-for="role in user.user_role" :key="role.id">
                            {{ role.discipline?.discipline || "N/A" }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div v-for="role in user.user_role" :key="role.id">
                            {{ role.program?.program || "N/A" }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div
                            v-for="(
                                role, institutionIndex
                            ) in user.user_role"
                            :key="role.id"
                        >
                            {{
                                role.institution?.name ||
                                institutionIndex == 0
                                    ? role.institution?.name
                                    : "N/A"
                            }}
                        </div>
                    </td>
                    <td>
                        <button @click="userLogin(user.id)" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded" :disabled="processing">LOGIN</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
    import { router } from '@inertiajs/vue3';
    import { reactive, ref } from 'vue';

    const props = defineProps([
        'user_list',
    ]);

    const processing = ref(false);

    const login = reactive({
        user: null,
    })

    function userLogin(id) {
        login.user = id;
        router.post('/testuserlogin', login, {
            onStart: () => {
                processing.value = true;
            },
            onFinish: () => {
                processing.value = false;
            },
        });
    }

    function testlogin() {
        router.post('/testuserlogin', login);
    }

</script>