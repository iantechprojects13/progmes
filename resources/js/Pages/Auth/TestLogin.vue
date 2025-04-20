<template>
    <Head title="Test User Login" />
    <div class="p-5 text-2xl font-bold">
        Test User Login
    </div>
    <div class="flex flex-col lg:flex-row p-5 gap-2 w-full">
        <div class="flex flex-col w-full lg:w-1/5">
            <label for="name">Name</label>
            <input type="text" v-model="query.name" class="rounded-xl border border-gray-300" placeholder="Enter Name">
        </div>
        <div class="flex flex-col w-full lg:w-1/5">
            <label for="role">Role</label>
            <select v-model="query.role" class="rounded-xl border border-gray-300">
                <option value="">Select role</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Regional Director">Regional Director</option>
                <option value="Chief Education Program Specialist">Chief Education Program Specialist</option>
                <option value="Supervising Education Program Specialist">Supervising Education Program Specialist</option>
                <option value="Education Supervisor">Education Supervisor</option>
                <option value="Vice-President for Academic Affairs">Vice-President for Academic Affairs</option>
                <option value="Librarian">Librarian</option>
                <option value="Dean">Dean</option>
                <option value="Program Head">Program Head</option>
            </select>
        </div>
        <div class="flex flex-col w-full lg:w-1/5">
            <label for="discipline">Discipline</label>
            <input type="text" v-model="query.discipline" class="rounded-xl border border-gray-300" placeholder="Enter Discipline">
        </div>
        <div class="flex flex-col w-full lg:w-1/5">
            <label for="program">Program</label>
            <input type="text" v-model="query.program" class="rounded-xl border border-gray-300" placeholder="Enter Program">
        </div>
        <div class="flex flex-col w-full lg:w-1/5">
            <label for="hei">Institution</label>
            <input type="text" v-model="query.institution" class="rounded-xl border border-gray-300" placeholder="Enter HEI">
        </div>
    </div>
    <div class="p-5 text-start w-full">
        <table class="text-left w-full">
            <thead>
                <tr>
                    <th class="bg-gray-300 py-4 px-2">Name</th>
                    <th class="bg-gray-300 py-4 px-2">Role</th>
                    <th class="bg-gray-300 py-4 px-2">Discipline</th>
                    <th class="bg-gray-300 py-4 px-2">Program</th>
                    <th class="bg-gray-300 py-4 px-2">HEI</th>
                    <th class="bg-gray-300 py-4 px-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(user, index) in user_list.data" :key="user.id" class="border-b border-gray-300 hover:bg-gray-200"
                :class="{'bg-gray-100' : index % 2 == 1}">
                    <td class="py-4 px-2">{{ user.name }}</td>
                    <td class="py-4 px-2">{{ user.role }}</td>
                    <td class="py-4 px-2">
                        <div v-for="role in user.user_role" :key="role.id">
                            {{ role.discipline?.discipline || "N/A" }}
                        </div>
                    </td>
                    <td class="py-4 px-2 max-w-[16rem]">
                        <div v-for="role in user.user_role" :key="role.id">
                            {{ role.program?.program }}
                            <span v-if="role.program?.major">
                                - {{ role.program?.major }}
                            </span>
                        </div>
                    </td>
                    <td class="py-4 px-2">
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
                    <td class="text-right py-4 px-2">
                        <button @click="userLogin(user.id)" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded" :disabled="processing">
                            Login
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div v-if="user_list.data.length === 0" class="py-4 text-center text-gray-500">
            No users found matching your filters.
        </div>
        
        <div v-if="user_list.links && user_list.links.length > 3" class="mt-4">
            <div class="flex flex-wrap -mb-1">
                <template v-for="(link, index) in user_list.links" :key="index">
                    <div v-if="link.url === null" 
                         class="px-4 py-2 mb-1 mr-1 text-gray-500" 
                         v-html="link.label"></div>
                    <Link v-else 
                          :href="link.url" 
                          class="px-4 py-2 mb-1 mr-1 bg-white border rounded" 
                          :class="{'bg-blue-500 text-white': link.active}"
                          preserve-scroll>{{ link.label }}</Link>
                </template>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <loading-screen v-if="processing" />
</template>

<script setup>
    import { router, Link } from '@inertiajs/vue3';
    import { reactive, ref, watch } from 'vue';
    import { Head } from '@inertiajs/vue3';
    

    const props = defineProps([
        'user_list',
        'filters',
    ]);

    const query = ref({
        name: props.filters.name || '',
        role: props.filters.role || '',
        discipline: props.filters.discipline || '',
        program: props.filters.program || '',
        institution: props.filters.institution || '',
    });

    const processing = ref(false);

    const login = reactive({
        user: null,
    });

    const user = reactive({
        name: null,
    });

    // Add debounce function to prevent too many requests
    function debounce(fn, delay) {
        let timeoutID = null;
        return function() {
            clearTimeout(timeoutID);
            const args = arguments;
            const that = this;
            timeoutID = setTimeout(function() {
                fn.apply(that, args);
            }, 50);
        };
    }

    // Watch for changes in the query object
    watch(
    query,
    debounce((newQuery) => {
        router.visit(route('test.login'), {
            data: newQuery,
            preserveState: true,
            preserveScroll: true,
            only: ['user_list', 'filters'],
            replace: true
        });
    }, 500),
    { deep: true }
);

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
</script>