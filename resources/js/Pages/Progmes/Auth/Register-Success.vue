<template>
    <Head title="Register" />
    <div class="w-full flex justify-center mt-20 select-none">
        <div
            class="bg-white mx-auto md:w-4/5 md:shadow md:shadow-gray-400 md:border md:border-gray-500 md:rounded max-w-md px-5 py-10 text-gray-700 text-center">
            <div>
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
            </div>
            <div class="my-5">
                <h1 class="text-lg md:text-xl font-bold text-blue-600">Registration Successful</h1>
            </div>
            <div class="mb-10 pb-10 border-b border-gray-400">
                <p>Thank you for registering.</p>
                <p>You will be notified via email once verified.</p>
            </div>
            <div class="text-center mt-3">
                <Link :href="route('logout')" method="post" as="button"
                    class="h-full w-32 bg-gray-600 hover:bg-gray-900 text-white rounded py-2 px-3">
                <i class="fas fa-sign-out mr-2"></i>
                Log out
                </Link>
            </div>
        </div>
    </div>
</template>