<template>

    <!-- Body -->
    <div class="flex flex-col md:flex-row h-full bg-neutral-900">
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
                            <div class="p-2 my-5 flex flex-row items-center select-none">
                                <img src="/assets/ched-logo.png" width="60" class="mr-3" />
                                <div class="text-2xl">ProgMES</div>
                            </div>
                            <div>
                                <div>
                                    <hr />
                                    <div class="select-none w-full h-12 rounded p-2 mt-3 relative flex items-center justify-start cursor-pointer">
                                        <div class="inline-block mr-3">
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
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="my-4 pt-5 border-t" @click="mobileSideBar">
                                <Link :href="route('dashboard')"
                                    class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{
                                        'text-blue-400 bg-slate-800':
                                            highlight('dashboard'),
                                    }">
                                <i class="fa fa-pie-chart mr-4 text-xl"></i>
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

                                <Link :href="'/myaccount/' + $page.props.auth.user.id"
                                    class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700"
                                    :class="{
                                        'text-blue-400 bg-slate-800':
                                            highlight('myaccount'),
                                    }">
                                <i class="fa fa-user mr-4 text-xl"></i>
                                My Account
                                </Link>

                                <button @click="toggleLogoutModal" :disable="loggingout" class="select-none block w-full text-start px-3 my-1 rounded-lg py-2 hover:bg-stone-700">
                                    <i class="fa fa-sign-out mr-4 text-xl"></i>
                                Log out
                                </button>
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
        <div class="w-full h-auto relative bg-gray-100 md:pt-0 pb-5">
            <div
                class="md:hidden sticky top-0 w-full bg-blue-900 text-white flex justify-between text-center h-16 items-center shadow-md z-80 px-4">
                <div class="cursor-pointer" @click="toggleSideBar">
                    <i class="fas fa-bars text-xl"></i>
                </div>
                <div class="flex flex-row items-center">
                    <div class="text-sm mr-2">Hi, {{ $page.props.auth.user.name }}</div>
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
                                <button @click="toggleLogoutModal" :disable="loggingout" class="block py-2 indent-3 hover:bg-gray-200 w-full text-left">
                                    <i class="fas fa-sign-out mr-3"></i>Log out
                                </button>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
            <div>
                <slot />
            </div>
        </div>
    </div>
    <div>
        <confirmation :showModal="logoutModal" @close="toggleLogoutModal" title="Confirm Logout" width="md" height="short">
            <template v-slot:message>
                <div>Are you sure you want to log out?</div>
            </template>
            <template v-slot:buttons>
                <button @click="logout" class="select-none text-white bg-red-500 hover:bg-red-600 h-10 w-20 rounded border">
                    <span v-if="loggingout">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Log out</span>
                </button>
            </template>
        </confirmation>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const logoutModal = ref(false);
const loggingout = ref(false);

function toggleLogoutModal() {
    logoutModal.value = !logoutModal.value;
}

function logout() {
    router.post('/logout', loggingout.value, {
        onStart: () => {
            loggingout.value = true;
        },
        onFinish: () => {
            loggingout.value = false;
        }
    });
}
</script>

<script>
    export default {
        data() {
            return {
                component: {
                    dashboard: [
                        "Progmes/Dashboard/Dashboard-CHED-Progress",
                        "Progmes/Dashboard/Dashboard-CHED-Overview",
                        "Progmes/Dashboard/Dashboard-CHED-Analytics",
                        "Progmes/Dashboard/Dashboard-HEI",
                        "Progmes/Dashboard/Dashboard-CHED",
                        "Progmes/Dashboard/CHED-ES-Dashboard",
                    ],
                    adminpanel: [
                        "Progmes/Admin/User-List",
                        "Progmes/Admin/User-Request",
                        "Progmes/Admin/User-Inactive",
                        "Progmes/Admin/CMO",
                        "Progmes/Admin/CMO-Edit",
                        "Progmes/Admin/CMO-Draft",
                        "Progmes/Admin/CMO-View",
                        "Progmes/Admin/EvaluationForm",
                        "Progmes/Admin/HEI",
                        "Progmes/Admin/HEI-Create",
                        "Progmes/Admin/HEI-Edit",
                        "Progmes/Admin/HEI-View",
                        "Progmes/Admin/Program",
                        "Progmes/Admin/Program-Create",
                        "Progmes/Admin/Program-Edit",
                        "Progmes/Admin/Discipline",
                        "Progmes/Admin/Discipline-Create",
                        "Progmes/Admin/Discipline-Edit",
                        "Progmes/Admin/Profile-View",
                    ],
                    evaluation: [
                        "Progmes/Evaluation/Evaluation-CHED",
                        "Progmes/Evaluation/Evaluation-HEI",
                        "Progmes/Evaluation/HEI-Evaluation-Edit",
                        "Progmes/Evaluation/HEI-Evaluation-View",
                        "Progmes/Evaluation/HEI-Evaluation-PH-Select",
                        "Progmes/Evaluation/HEI-Evaluation-Select",
                        "Progmes/Evaluation/HEI-Evaluation-Monitored",
                        "Progmes/Evaluation/CHED-Evaluation-Select",
                        "Progmes/Evaluation/CHED-Evaluation-Edit",
                        "Progmes/Evaluation/CHED-Evaluation-View",
                        "Progmes/Evaluation/CHED-Evaluation-Report-Create",
                        "Progmes/Evaluation/CHED-Evaluation-Monitored",
                    ],
                    myaccount: [
                        "Progmes/Auth/Account",
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
                } else if (btn == "myaccount") {
                    return this.component.myaccount.includes(this.$page.component);
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