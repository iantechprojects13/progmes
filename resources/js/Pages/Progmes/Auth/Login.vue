<template>
    <Head title="Login" />
    <div class="w-full flex h-screen select-none">
        <div class="w-full h-screen flex text-center items-center justify-center"
            style="background-size: cover; background-position: center; background-image: linear-gradient(#252525c7, #252525c7), url(assets/ched-office.jpg); background-size: auto 100%;">
            <div>
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
                    <a :href="route('auth.google')">
                        <button class="hover:bg-blue-500 hover:text-white bg-white font-bold py-0 px-3 rounded-full flex items-center
                justify-center border border-gray-400 mx-auto">
                            <img src="assets/google.png" width="40" class="p-1 inline top-0 mr-1">
                            Sign in with Google
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
                <a :href="route('auth.google')">
                    <button class="hover:bg-blue-500 hover:text-white font-bold px-3 py-0 rounded-full flex items-center
                        justify-center border border-gray-400 mx-auto">
                        <img src="assets/google.png" width="40" class="p-1 inline top-0 mr-1">
                        Sign in with Google
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>