<template>
    <Head title="Register" />
    <div class="w-full bg-white flex justify-center mt-20 select-none">
        <div
            class="mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:border-gray-400 md:rounded max-w-lg px-10 p-5 pb-10 text-gray-600">
            <div class="p-2 mb-5">
                <h1 class="font-bold text-xl text-gray-700 text-center mb-5">ProgMES CHED RO XI</h1>
                <p class="text-gray-700 text-center text-sm">Before you continue, please take a moment
                    to complete your account setup.</p>
            </div>
            <div class="w-full text-center pt-5 border-t border-gray-400">
                <h1 class="text-md font-bold text-blue-600">Choose Account Type</h1>
                <Link :href="route('register.ched')">
                <button
                    class="hover:text-white hover:bg-blue-800 text-sm p-3 mt-5 w-full border border-gray-400 rounded py-3">
                    Commission on Higher Education (CHED)
                </button>
                </Link>
                <Link :href="route('register.hei')">
                <button
                    class="hover:text-white hover:bg-blue-800 text-sm p-3 mt-5 w-full border border-gray-400 rounded py-3">
                    Higher Education Institution (HEI)
                </button>
                </Link>
            </div>
        </div>
    </div>
</template>
