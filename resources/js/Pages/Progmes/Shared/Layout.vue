<template>

    <!-- Body -->
    <div class="flex flex-col md:flex-row h-full bg-gray-200">
        <!-- Navigation -->
        <div class="fixed z-90 top-0 w-full h-full bg-black bg-opacity-70 md:hidden" :class="{ hidden: closeSideBar }"
            @click="toggleSideBar()"></div>
        <div class="h-full absolute top-0 w-64 left-0 md:relative z-90 transition-all duration-200 bg-opacity-20"
            :class="{
                'md:w-0 -translate-x-64 md:sticky md:top-0': closeSideBar,
            }" @click.self="toggleSideBar()">
            <div class="md:block md:w-64">
                <div
                    class="flex-col h-screen w-64 bg-neutral-900 md:w-64 pt-1 pb-3 px-4 text-gray-200 md:block top-0 left-0 fixed">
                    <div class="flex flex-col justify-between h-full md:sticky md:top-0">
                        <div class="flex-shrink-0">
                            <div class="md:hidden w-full text-right pt-2">
                                <button @click="toggleSideBar" class="border px-2.5 py-0.5 hover:bg-stone-800">
                                    <i class="fas fa-angle-left"></i>
                                </button>
                            </div>

                            <!-- App Name -->
                            <div class="p-2 my-3 flex flex-row items-center select-none">
                                <img src="/assets/ched-logo.png" width="50" class="mr-3" />
                                <div class="text-2xl">ProgMES</div>
                            </div>

                            <!-- Searchbox -->
                            <div class="bg-gray-700 my-7 h-10 rounded-md relative">
                                <form class="h-full">
                                    <span
                                        class="w-1/6 h-full text-gray-400 p-1 absolute left-1 bg-inherit rounded flex justify-center items-center">
                                        <i class="fas fa-search ye"></i>
                                    </span>
                                    <input type="text"
                                        class="inline-block w-full h-full bg-inherit border-none rounded-md placeholder-gray-400 text-sm indent-10"
                                        name="search" placeholder="Search" />
                                </form>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="my-4" @click="mobileSideBar">
                                <Link :href="route('dashboard')"
                                    class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{
                                        'text-blue-400 bg-slate-800':
                                            highlight('dashboard'),
                                    }">
                                <i class="fa fa-th-large mr-4 text-xl"></i>
                                Dashboard
                                </Link>

                                <Link :href="route('admin.users.list')" v-if="$page.props.auth.user.type == 'CHED'"
                                    class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{
                                        'text-blue-400 bg-slate-800':
                                            highlight('adminpanel'),
                                    }">
                                <i class="fas fa-shield mr-4 text-xl"></i>
                                Admin Panel
                                </Link>

                                <Link :href="route('evaluation')"
                                    class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{
                                        'text-blue-400 bg-slate-800':
                                            highlight('evaluation'),
                                    }">
                                <i class="fa fa-edit mr-4 text-xl"></i>
                                Evaluation
                                </Link>
                            </div>
                        </div>

                        <!-- Profile -->
                        <div class="md:block flex-shrink-0 hidden relative" @click="isOpen = true">
                            <div>
                                <hr />
                                <div ref="profileContainer"
                                    class="w-full h-12 rounded p-2 mt-3 relative flex items-center justify-start hover:bg-stone-700 cursor-pointer">
                                    <div class="inline-block mr-2">
                                        <img :src="$page.props.auth.user.avatar" width="40" class="rounded-full" />
                                    </div>
                                    <div class="inline-block overflow-hidden" :title="$page.props.auth.user.name">
                                        <div class="text-sm whitespace-nowrap overflow-hidden overflow-ellipsis">
                                            {{ $page.props.auth.user.name }}
                                        </div>
                                        <p class="text-xs whitespace-nowrap overflow-hidden overflow-ellipsis">
                                            {{ $page.props.auth.user.role }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div v-if="isOpen"
                                class="md:block h-auto w-full hidden absolute bottom-14 bg-stone-700 rounded py-3">
                                <Link :href="'/myaccount/' + $page.props.auth.user.id">
                                <button class="block py-2 indent-3 hover:bg-stone-600 w-full text-left">
                                    <i class="fas fa-user mr-3"></i>My Account
                                </button>
                                </Link>
                                <Link href="/logout" method="post" as="button" class="w-full">
                                <button class="block py-2 indent-3 hover:bg-stone-600 w-full text-left">
                                    <i class="fas fa-sign-out mr-3"></i>Log out
                                </button>
                                </Link>
                            </div>
                        </div>

                        <div class="absolute top-1/2 -right-4 text-black hidden md:block text-md" :class="{
                                'fixed top-0 bg-slate-700': !closeSideBar,
                            }">
                            <div v-if="!closeSideBar"
                                class="sticky top-0 w-8 flex items-center justify-center font-bold cursor-pointer custom-tooltip hover:text-xl transition-all duration-200"
                                data-tooltip="Close sidebar" @click="
                                    toggleSideBar();
                                    autoHideSideBar(false);
                                ">
                                <i class="fas fa-angle-left"></i>
                            </div>
                            <div v-else
                                class="absolute top-0 w-8 flex items-center justify-center font-bold cursor-pointer custom-tooltip hover:text-xl transition-all duration-200"
                                data-tooltip="Open sidebar" @click="
                                    toggleSideBar();
                                    autoHideSideBar(true);
                                ">
                                <i class="fas fa-angle-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="w-full h-auto overflow-y-hidden relative bg-gray-200 overflow-x-auto md:pt-0">
            <div
                class="md:hidden sticky top-0 w-full bg-blue-900 text-white flex justify-between text-center h-16 items-center shadow-md z-80 px-4">
                <div class="cursor-pointer" @click="toggleSideBar">
                    <i class="fas fa-bars text-xl"></i>
                </div>
                <div>
                    <dropdown-option position="right">
                        <template v-slot:button>
                            <img :src="$page.props.auth.user.avatar" width="35"
                                class=" border-2 bg-white rounded-full cursor-pointer" />
                        </template>
                        <template v-slot:options>
                            <div class="w-52">
                                <Link :href="'/myaccount/' + $page.props.auth.user.id">
                                <button class="block py-2 indent-3 hover:bg-gray-200 w-full text-left">
                                    <i class="fas fa-user mr-3"></i>My Account
                                </button>
                                </Link>
                                <Link href="/logout" method="post" as="button" class="w-full">
                                <button class="block py-2 indent-3 hover:bg-gray-200 w-full text-left">
                                    <i class="fas fa-sign-out mr-3"></i>Log out
                                </button>
                                </Link>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>

<script setup>
    import { onMounted, onBeforeUnmount, ref } from 'vue';

    const profileContainer = ref(null);
    const isOpen = ref(false);


    const closeOption = (element) => {
        if (!profileContainer.value.contains(element.target)) {
            isOpen.value = false;
        }
    }

    onMounted(() => {
        window.addEventListener('click', closeOption);
    });

    onBeforeUnmount(() => {
        window.removeEventListener('click', closeOption);
    });

</script>

<script>
    export default {
        data() {
            return {
                component: {
                    dashboard: [
                        "Progmes/Dashboard/Dashboard-CHED",
                        "Progmes/Dashboard/Dashboard-HEI",
                        "Progmes/Dashboard/CHED-ES-Dashboard",
                    ],
                    adminpanel: [
                        "Progmes/Admin/User-List",
                        "Progmes/Admin/User-Request",
                        "Progmes/Admin/HEI",
                        "Progmes/Admin/HEI-Create",
                        "Progmes/Admin/HEI-View",
                        "Progmes/Admin/CMO",
                        "Progmes/Admin/CMO-Edit",
                        "Progmes/Admin/CMO-Upload",
                        "Progmes/Admin/Settings",
                        "Progmes/Admin/EvaluationForm",
                        "Progmes/Admin/Program",
                        "Progmes/Admin/Program-Create",
                    ],
                    evaluation: [
                        "Progmes/Evaluation/Evaluation-CHED",
                        "Progmes/Evaluation/Evaluation-HEI",
                        "Progmes/Evaluation/HEI-Evaluation-Edit",
                        "Progmes/Evaluation/HEI-Evaluation-PH-Select",
                        "Progmes/Evaluation/CHED-Evaluation-ES-Select",
                        "Progmes/Evaluation/CHED-Evaluation-Admin-Select",
                        "Progmes/Evaluation/CHED-Evaluation-Edit",
                    ],
                },
                closeSideBar: false,
                autoHide: true,
                sideBarUser: false,
                userOption: false,
            };
        },

        methods: {
            highlight(btn) {
                if (btn == "dashboard") {
                    return this.component.dashboard.includes(this.$page.component);
                } else if (btn == "adminpanel") {
                    return this.component.adminpanel.includes(this.$page.component);
                } else if (btn == "evaluation") {
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
                this.closeSideBar =
                    window.innerWidth >= 768 && this.autoHide ? false : true;
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
            window.addEventListener("resize", this.handleResize);

            if (window.innerWidth < 768) {
                this.closeSideBar = true;
            }
        },
    };
</script>