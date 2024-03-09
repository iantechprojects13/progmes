<template>

    <Head title="Register" />
    <div class="w-full flex justify-center mt-20 select-none">
        <div
            class="bg-white mx-5 md:mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:border-gray-500 md:rounded max-w-lg px-10 p-5 pb-10 text-gray-600">
            <div class="p-2 mb-5">
                <div class="mb-5">
                    <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
                </div>
                <h1 class="font-bold text-xl md:text-3xl text-gray-700 text-center mb-5">ProgMES <span
                        class="text-red-600">|</span> CHED RO XI</h1>
                <p class="text-gray-700 text-center text-base">Before you continue, please take a moment
                    to complete your account setup.</p>
            </div>
            <div class="w-full text-center pt-5 border-t border-gray-400">
                <h1 class="text-lg font-bold text-blue-600">Choose Account Type</h1>
                <Link :href="route('register.ched')">
                <button
                    class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full text-gray-700 border border-gray-500 rounded py-3">
                    Commission on Higher Education (CHED)
                </button>
                </Link>
                <Link :href="route('register.hei')">
                <button
                    class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full text-gray-700 border border-gray-500 rounded py-3">
                    Higher Education Institution (HEI)
                </button>
                </Link>
            </div>
        </div>
    </div>
</template>