<template>
    <Head title="Login" />
    <div class="w-full flex h-screen">
        <div class="w-full h-screen flex text-center items-center justify-center" id="background-pic">
            <div>
                <div class="w-full text-white">
                    <img src="assets/ched-logo.png" width="100" class="mx-auto mb-5 lg:hidden">
                    <p class="text-3xl font-bold">CHEDRO-XI</p>
                    <br>
                    <p class="text-5xl font-bold text-yellow-400">ProgMES</p>
                    <br>
                    <p class="lg:w-3/4 md:w-4/6 w-11/12 mx-auto text-3xl">A Web-based Program Monitoring and Evaluation
                        System</p>
                </div>
                <div class="p-3 rounded shadow-md w-full text-center lg:hidden mx-auto mt-5">
                    <a :href="route('auth.google')">
                        <button class="hover:bg-blue-500 hover:text-white bg-white font-bold py-2 px-4 rounded-full flex items-center
                justify-center border border-gray-400 mx-auto">
                            <img src="assets/google.png" width="40" class="p-1 inline top-0 mr-1">
                            Sign in with Google
                        </button>
                    </a>
                </div>
            </div>

        </div>
        <div class="h-screen lg:flex items-center justify-center w-4/12 hidden">
            <div class="bg-white px-5 py-10 w-full text-center">
                <div class="w-full text-center justify-center">
                    <img src="assets/ched-logo.png" width="100" class="mx-auto">
                </div>
                <div class="mb-7 mt-5 mx-auto w-64">
                    <h1 class="text-3xl font-bold">SIGN IN</h1>
                    <p class="text-sm text-gray-500">to continue to ProgMES</p>
                </div>
                <a :href="route('auth.google')">
                    <button class="hover:bg-blue-500 hover:text-white font-bold px-4 py-1 rounded-full flex items-center
                        justify-center border border-gray-400 mx-auto">
                        <img src="assets/google.png" width="40" class="p-1 inline top-0 mr-1">
                        Sign in with Google
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<!-- script -->

<script setup>

import { useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>


<!-- style -->

<style scoped>
#background-pic {
    background-size: cover;
    background-position: center;
    background-image: linear-gradient(#252525c7,
            #252525c7),
        url(assets/ched-office.jpg);
    background-size: auto 100%;
}
</style>