<template>
    <Head title="Register" />
    <div class="w-full bg-white flex justify-center mt-20">
        <div class="mx-auto md:w-4/5 md:shadow-lg md:border md:rounded-lg max-w-md p-5 pb-10 text-gray-600">
            <div class="p-2 mb-7">
                <h1 class="font-bold text-lg text-center mb-5">ProgMES | CHEDRO-XI</h1>
                <p class="text-gray-500 text-center text-sm">Before you continue, please take a moment
                    to complete your account setup.</p>
            </div>
            <hr>
            <div class="w-full text-center mt-10">
                <h1 class="text-md font-bold text-blue-600">Choose Account Type</h1>
                <Link :href="route('register.ched')">
                <button class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full border border-gray-400 rounded-xl py-3">
                    Commission on Higher Education (CHED)
                </button>
                </Link>
                <Link :href="route('register.hei')">
                <button class="hover:text-white hover:bg-blue-800 p-3 mt-5 w-full border border-gray-400 rounded-xl py-3">
                    Higher Education Institution (HEI)
                </button>
                </Link>
            </div>
        </div>
    </div>
</template>