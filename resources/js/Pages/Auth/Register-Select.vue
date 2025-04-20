<template>
    <Head title="Choose Account Type | Register" />
    <div class="w-full flex justify-center mt-20 select-none">
        <div
            class="bg-white mx-5 md:mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:border-gray-500 md:rounded max-w-lg px-10 p-5 pb-10 text-gray-600">
            <div class="p-2 mb-5">
                <div class="mb-5">
                    <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                </div>
                <h1 class="font-bold text-xl md:text-3xl text-gray-700 text-center mb-5">ProgMES <span
                        class="text-red-600">|</span> CHED RO XI</h1>
                <p class="text-gray-700 text-center text-base">
                    Hello, <span class="font-semibold">{{ $page.props.auth.user.name || $page.props.auth.user.email }}</span>!
                    Before you continue, please take a moment to complete your account setup.
                </p>
            </div>
            <div class="w-full text-center pt-5 border-t border-gray-400">
                <h1 class="text-lg font-bold text-blue-600">Choose Account Type</h1>
                <button :disabled="processing" @click="registerTypeCHED"
                    class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full text-gray-700 border border-gray-500 rounded py-3">
                    <span v-if="registerAsCHED"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else>Commission on Higher Education (CHED)</span>
                </button>
                <button :disabled="processing" @click="registerTypeHEI"
                    class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full text-gray-700 border border-gray-500 rounded py-3">
                    <span v-if="registerAsHEI"><i class="fas fa-spinner animate-spin"></i></span>
                    <span v-else>Higher Education Institution (HEI)</span>
                </button>
            </div>
        </div>
    </div>
</template>


<script setup>
    import { router } from "@inertiajs/vue3";
    import { ref } from 'vue';

    const processing = ref(false);
    const registerAsCHED = ref(false);
    const registerAsHEI = ref(false);

    function registerTypeCHED() {
        router.get('/register/commission-on-higher-education', {
            onStart: () => {
                processing.value = true;
                registerAsCHED.value = true;
            },
            onFinish: () => {
                processing.value = false;
                registerAsCHED.value = false;
            },
            preserveState: false,
            replace: true,
        })
    }

    function registerTypeHEI() {
        router.get('/register/higher-education-institution', {
            onStart: () => {
                processing.value = true;
                registerAsHEI.value = true;
            },
            onFinish: () => {
                processing.value = false;
                registerAsHEI.value = false;
            },
            preserveState: false,
            replace: true,
        })
    }
</script>