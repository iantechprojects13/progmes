<template>
    <Head title="Login" />
    <!-- Header -->
    <div
        class="md:hidden sticky top-0 bg-blue-900 text-white flex justify-between text-center h-16 items-center shadow-md z-90 px-4">
        <div class="cursor-pointer" @click="toggleSideBar">
            <i class="fas fa-bars text-xl"></i>
        </div>
        <div class="text-white font-bold">ProgMES | CHED RO XI</div>
        <div>
            <img :src="this.$page.props.auth.user.avatar" width="35" class="rounded-full">
        </div>
    </div>

    <!-- Body -->
    <div class="flex flex-col md:flex-row h-full bg-gray-200">

        <!-- Navigation -->
        <div class="fixed z-80 top-0 w-full h-full bg-black bg-opacity-70 md:hidden" :class="{ 'hidden': closeSideBar }"
            @click="toggleSideBar()">
        </div>
        <div class="h-full absolute top-0 w-64 left-0 md:relative z-90 transition-all duration-200 bg-opacity-20" :class="{
            'md:w-0 -translate-x-64 md:sticky md:top-0': closeSideBar
        }" @click.self="toggleSideBar()">
            <div class="md:block md:w-64">
                <div class=" flex-col h-screen w-64 bg-neutral-900 md:w-64 pt-1 pb-3 px-4 text-gray-200 md:block top-0
                left-0 fixed">
                    <div class="flex flex-col justify-between h-full md:sticky md:top-0">
                        <div class="flex-shrink-0">

                            <div class="md:hidden w-full text-right pt-2">
                                <button @click="toggleSideBar" class="border px-2.5 py-0.5 hover:bg-stone-800"><i
                                        class="fas fa-angle-left"></i></button>
                            </div>

                            <!-- App Name -->
                            <div class="p-2 my-2">
                                <img src="/assets/ched-logo.png" width="30" class="inline-block mr-3">
                                <div class="inline-block relative top-1">
                                    ProgMES
                                </div>
                            </div>

                            <!-- Searchbox -->
                            <div class="bg-gray-700 mt-0 mb-5 h-10 rounded-md relative">
                                <form class="h-full">
                                    <span
                                        class="w-1/6 h-full text-gray-400 p-1 absolute left-1 bg-inherit rounded flex justify-center items-center">
                                        <i class="fas fa-search ye"></i>
                                    </span>
                                    <input type="text" class="inline-block w-full h-full bg-inherit border-none rounded-md placeholder-gray-400
                                text-sm indent-10" name="search" placeholder="Search">
                                </form>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="my-4" @click="mobileSideBar">
                                <Link :href="route('dashboard')" preserve-scroll
                                    class="block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{ 'text-blue-400 bg-slate-800': highlight('dashboard') }">
                                <i class="fa fa-th-large mr-4 text-xl"></i>
                                Dashboard
                                </Link>

                                <Link :href="route('admin.users.list')" preserve-scroll
                                    v-if="$page.props.auth.user.type == 'CHED'"
                                    class="block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{ 'text-blue-400 bg-slate-800': highlight('adminpanel') }">
                                <i class="fas fa-shield mr-4 text-xl"></i>
                                Admin Panel
                                </Link>

                                <Link :href="route('evaluation')"
                                    class="block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{ 'text-blue-400 bg-slate-800': highlight('evaluation') }">
                                <i class="fa fa-edit mr-4 text-xl"></i>
                                Evaluation
                                </Link>
                                <Link href="" class="block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700">
                                <i class="fa fa-archive mr-4 text-xl"></i>
                                Archive
                                </Link>
                            </div>
                        </div>

                        <!-- Profile -->
                        <div class="md:block flex-shrink-0 hidden relative" @click="toggleSideBarUser">
                            <div>
                                <hr>
                                <div
                                    class=" w-full h-12 rounded p-2 mt-3 relative flex items-center justify-center hover:bg-stone-700 cursor-pointer">
                                    <div class="inline-block mr-2">
                                        <img :src="this.$page.props.auth.user.avatar" width="40" class="rounded-full">
                                    </div>
                                    <div class="inline-block overflow-hidden" :title="this.$page.props.auth.user.name">
                                        <div class="text-sm whitespace-nowrap overflow-hidden overflow-ellipsis">{{
                                            this.$page.props.auth.user.name }}</div>
                                        <p class="text-xs whitespace-nowrap overflow-hidden overflow-ellipsis">{{
                                            this.$page.props.auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="sideBarUser"
                                class="md:block h-auto w-full hidden absolute bottom-14 bg-stone-700 rounded py-3">
                                <Link class="block py-2 indent-3 hover:bg-stone-600">
                                <i class="fas fa-user mr-3"></i>My Profile
                                </Link>
                                <Link :href="route('logout')" method="post" as="button"
                                    class="block py-2 indent-3 hover:bg-stone-600 w-full text-left">
                                <i class="fas fa-sign-out mr-3"></i>Log out
                                </Link>
                            </div>
                        </div>

                        <div class="absolute top-1/2 -right-4 text-black hidden md:block text-md"
                            :class="{ 'fixed top-0 bg-slate-700': !closeSideBar }">
                            <div v-if="!closeSideBar"
                                class="sticky top-0 w-8 flex items-center justify-center font-bold cursor-pointer custom-tooltip hover:text-xl transition-all duration-200"
                                data-tooltip="Close sidebar" @click="toggleSideBar(); autoHideSideBar(false)">
                                <i class="fas fa-angle-left"></i>
                            </div>
                            <div v-else
                                class="absolute top-0 w-8 flex items-center justify-center font-bold cursor-pointer custom-tooltip hover:text-xl transition-all duration-200"
                                data-tooltip="Open sidebar" @click="toggleSideBar(); autoHideSideBar(true)">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="w-full h-screen relative bg-white overflow-x-auto">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            component:
            {
                dashboard: [
                    'Progmes/Dashboard/Dashboard-CHED', 'Progmes/Dashboard/Dashboard-HEI',
                ],
                adminpanel: [
                    'Progmes/Admin/User-List', 'Progmes/Admin/User-Request', 'Progmes/Admin/HEI',
                    'Progmes/Admin/HEI-Create', 'Progmes/Admin/HEI-View',
                    'Progmes/Admin/CMO', 'Progmes/Admin/CMO-Edit', 'Progmes/Admin/CMO-Upload',
                    'Progmes/Admin/Settings', 'Progmes/Admin/EvaluationForm',
                    'Progmes/Admin/Program', 'Progmes/Admin/Program-Create',
                ],
                evaluation: [
                    'Progmes/Evaluation/Evaluation-CHED', 'Progmes/Evaluation/Evaluation-HEI',
                    'Progmes/Evaluation/Evaluation-HEI-Edit', 'Progmes/Evaluation/Evaluation-Admin',
                ],
            },
            closeSideBar: false,
            autoHide: true,
            sideBarUser: false,
            userOption: false,
        }
    },

    methods: {
        highlight(btn) {
            if (btn == 'dashboard') {
                return this.component.dashboard.includes(this.$page.component);
            } else if (btn == 'adminpanel') {
                return this.component.adminpanel.includes(this.$page.component);
            } else if (btn == 'evaluation') {
                return this.component.evaluation.includes(this.$page.component);
            }
        },

        toggleSideBar() {
            this.closeSideBar = !this.closeSideBar;
        },

        toggleSideBarUser() {
            this.sideBarUser = !this.sideBarUser;
        },

        hoverSideBarUser() {
            if (!this.sideBarUser) {
                this.sideBarUser = false;
            } else {
                this.sideBarUser = !this.sideBarUser;
            }
        },

        autoHideSideBar(autohide) {
            this.autoHide = autohide;
        },

        handleResize() {
            this.closeSideBar = window.innerWidth < 768 ? true : false;
            this.closeSideBar = window.innerWidth >= 768 && this.autoHide ? false : true;
        },

        mobileSideBar() {
            if (window.innerWidth < 768) {
                this.closeSideBar = true;
            }
        },

        showUserOption() {
            this.userOption = true;
        },

        hideUserOption() {
            this.userOption = false;
        },


    },

    mounted() {
        window.addEventListener('resize', this.handleResize);

        if (window.innerWidth < 768) {
            this.closeSideBar = true;
        }

    },
}
</script>
