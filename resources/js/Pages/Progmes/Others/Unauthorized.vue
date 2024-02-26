<template>
    <Head title="401 Unauthorized Access" />
    <div class="w-full h-screen flex flex-col justify-center items-center">
        <div
            class="md:shadow-lg md:shadow-gray-500 md:-translate-y-24 p-5 md:border md:border-gray-500 max-w-lg md:rounded">
            <div class="flex flex-col md:flex-row items-center">
                <div class="mr-6 text-7xl  text-red-500"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="border-md text-center md:text-start">
                    <p class="font-bold text-lg">401 Unauthorized Access</p>
                    <p class="text-base">Sorry, but you don't have the necessary permissions to access this resource. </p>
                </div>
            </div>

            <div class="mt-5 w-full text-center md:text-end">
                <Link :href="route('dashboard')" class="px-3 py-2 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-2"></i>
                Go to dashboard
                </Link>
            </div>
        </div>
    </div>
</template>

