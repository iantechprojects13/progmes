<template>
    <Head title="Register" />
    <div class="w-full bg-white flex justify-center mt-20">
        <div class="mx-auto md:w-4/5 md:shadow-lg md:border md:rounded-lg max-w-md p-5 pb-5 text-gray-600 text-center">
            <div>
                <img src="/assets/ched-logo.png" alt="CHED Logo" width="75" class="mx-auto">
            </div>
            <div class="my-5">
                <h1 class="text-lg font-bold text-blue-500">Registration Successful</h1>
            </div>
            <div class="mb-10">
                <p>Thank you for registering.</p>
                <p>You will be notified via email once verified.</p>
            </div>
            <hr>
            <div class="text-right mt-3">
                <Link :href="route('logout')" method="post" as="button"
                    class="h-full w-auto bg-gray-600 hover:bg-gray-900 text-white rounded-lg py-2 px-3">Log
                out
                </Link>
            </div>
        </div>
    </div>
</template>