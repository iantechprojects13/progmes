<template>
    <Head title="Login" />

    <div class="w-full flex h-screen select-none">
        <div class="w-full h-screen flex text-center items-center justify-center"
            style="background-size: cover; background-position: center; background-image: linear-gradient(#252525c7, #252525c7), url(assets/ched-office.jpg); background-size: auto 100%;">
            <div class="-mt-10 md:mt-5">
                <div class="w-full text-white">
                    <img src="assets/ched-logo.png" width="100" class="mx-auto mb-5 lg:hidden">
                    <p class="text-3xl lg:text-5xl font-bold">CHED RO XI</p>
                    <br>
                    <p class="text-3xl lg:text-5xl font-bold text-yellow-400">ProgMES</p>
                    <br>
                    <p class="lg:w-3/4 md:w-4/6 w-11/12 mx-auto text-xl lg:text-3xl">A Web-based Program Monitoring and
                        Evaluation
                        System</p>
                </div>
                <div class="p-3 rounded shadow-md w-full text-center lg:hidden mx-auto mt-5">
                    <a @click.prevent="login" href="#" class="inline-block">
                        <button :disabled="isLoggingIn" class="active:bg-blue-500 bg-white hover:bg-blue-300 active:text-white font-bold px-5 py-2 rounded-full flex items-center
                            justify-center border-2 border-gray-400 hover:border-gray-700 mx-auto shadow-lg shadow-gray-900 disabled:opacity-70 disabled:cursor-not-allowed w-64 h-12">
                            <div class="flex items-center justify-center w-full">
                                <div class="w-10 h-10 flex items-center justify-center">
                                    <img v-if="!isLoggingIn" src="assets/google.png" width="40" class="p-1">
                                    <svg v-else class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <span class="ml-1">{{ isLoggingIn ? 'Signing in...' : 'Sign in with Google' }}</span>
                            </div>
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="h-screen bg-white lg:flex items-center justify-center w-4/12 hidden">
            <div class="text-center">
                <div class="w-full text-center justify-center">
                    <img src="assets/ched-logo.png" width="75" class="mx-auto">
                </div>
                <div class="my-7 mx-auto">
                    <h1 class="text-3xl font-bold tracking-tighter">SIGN IN</h1>
                    <p class="text-gray-700 text-sm">to continue to ProgMES</p>
                </div>
                <a @click.prevent="login" href="#" class="inline-block">
                    <button :disabled="isLoggingIn" class="active:bg-blue-500 hover:bg-blue-300 active:text-white font-bold px-5 py-2 rounded-full flex items-center
                        justify-center border border-gray-400 hover:border-gray-500 mx-auto shadow-lg shadow-gray-400 disabled:opacity-70 disabled:cursor-not-allowed w-64 h-12">
                        <div class="flex items-center justify-center w-full">
                            <div class="w-10 h-10 flex items-center justify-center">
                                <img v-if="!isLoggingIn" src="assets/google.png" width="40" class="p-1">
                                <svg v-else class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <span class="ml-1">{{ isLoggingIn ? 'Signing in...' : 'Sign in with Google' }}</span>
                        </div>
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const isLoggingIn = ref(false);

const login = () => {
    isLoggingIn.value = true;
    window.location.href = route('auth.google');
};
</script>