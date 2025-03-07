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
                                    class="select-none block w-full text-start px-3 my-1 rounded py-2 hover:bg-stone-700"
                                    :class="[{
                                        'text-blue-500 bg-white hover:bg-white':
                                            highlight('dashboard'),
                                    }, {
                                        'text-gray-200 hover:text-blue-500':
                                            !highlight('dashboard'),
                                    }]"
                                    :disabled="hasChanges">
                                <i class="fa fa-pie-chart mr-4 text-xl"></i>
                                Dashboard
                                </Link>

                                <Link :href="route('admin.users.list')" v-if="$page.props.auth.user.type == 'CHED'"
                                    class="select-none block w-full text-start px-3 my-1 rounded py-2 hover:bg-stone-700"
                                    :class="[{
                                        'text-blue-500 bg-white hover:bg-white':
                                            highlight('adminpanel'),
                                    }, {
                                        'text-gray-200 hover:text-blue-500':
                                            !highlight('adminpanel'),
                                    }]"
                                    :disabled="hasChanges">
                                <i class="fas fa-shield mr-4 text-xl"></i>
                                Admin Panel
                                </Link>

                                <Link :href="route('evaluation')"
                                    class="select-none block w-full text-start px-3 my-1 rounded py-2 hover:bg-stone-700"
                                    :class="[{
                                        'text-blue-500 bg-white hover:bg-white':
                                            highlight('evaluation'),
                                    }, {
                                        'text-gray-200 hover:text-blue-500':
                                            !highlight('evaluation'),
                                    }]"
                                    :disabled="hasChanges">
                                <i class="fa fa-edit mr-4 text-xl"></i>
                                Evaluation
                                </Link>

                                <Link :href="route('application')"
                                    class="select-none block w-full text-start px-3 my-1 rounded py-2 hover:bg-stone-700"
                                    :class="[{
                                        'text-blue-500 bg-white hover:bg-white':
                                            highlight('application'),
                                    }, {
                                        'text-gray-200 hover:text-blue-500':
                                            !highlight('application'),
                                    }]"
                                    :disabled="hasChanges">
                                <i class="fa fa-user mr-4 text-xl"></i>
                                Program Application
                                </Link>

                                <Link :href="'/myaccount/' + $page.props.auth.user.id"
                                    class="select-none block w-full text-start px-3 my-1 rounded py-2 hover:bg-stone-700"
                                    :class="[{
                                        'text-blue-500 bg-white hover:bg-white':
                                            highlight('myaccount'),
                                    }, {
                                        'text-gray-200 hover:text-blue-500':
                                            !highlight('myaccount'),
                                    }]"
                                    :disabled="hasChanges">
                                <i class="fa fa-user mr-4 text-xl"></i>
                                My Account
                                </Link>

                                <button @click="toggleLogoutModal" :disable="loggingout || hasChanges" class="select-none block w-full text-gray-200 hover:text-red-500 text-start px-3 my-1 rounded py-2 hover:bg-stone-700">
                                    <i class="fa fa-sign-out mr-4 text-xl"></i>
                                Sign out
                                </button>
                            </div>




                            <div>
                                <!-- <div>
                                    <h1>Layout</h1>
                                    <p>Data from Child: {{ childData.value }}</p>
                                </div> -->
                            </div>





                        </div>
                        
                        <!-- Sidebar Toggle -->
                        <div class="absolute top-1/2 -right-4 text-gray-400 hidden md:block text-md"
                            :class="{ 'fixed top-0 bg-slate-700': !closeSideBar }">
                            <div class="w-8 flex items-center justify-center font-bold cursor-pointer layout-tooltip hover:text-black hover:text-xl transition-all duration-200"
                                :data-tooltip="closeSideBar ? 'Open sidebar' : 'Close sidebar'"
                                @click="toggleSideBar(); autoHideSideBar(!closeSideBar);">
                                <i :class="closeSideBar ? 'fas fa-angle-right' : 'fas fa-angle-left'"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Page Content -->
        <div class="w-full h-auto relative bg-white md:pt-0 pb-5">
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
        <confirmation :showModal="logoutModal" @close="toggleLogoutModal" title="Confirm Exit" width="md" height="short">
            <template v-slot:message>
                <div>Are you sure you want to sign out?</div>
            </template>
            <template v-slot:buttons>
                <button @click="logout" class="select-none text-white bg-red-500 hover:bg-red-600 h-10 w-20 rounded border">
                    <span v-if="loggingout">
                        <i class="fas fa-spinner animate-spin"></i>
                    </span>
                    <span v-else>Sign out</span>
                </button>
            </template>
        </confirmation>
    </div>


    <!-- Loading -->
    <div v-if="loading" class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-70">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mx-auto"></div>
            <div class="mt-4 text-white text-lg text-center">Please wait...</div>
        </div>
    </div>
    
    <!-- Notifications -->
    <TransitionGroup name="notifications" tag="div" class="fixed bottom-0 right-0 z-90 p-4 space-y-2">
        <div v-for="notification in activeNotifications" :key="notification.id"
          class="bg-stone-800 text-white p-5 w-full md:max-w-md flex justify-between items-center">
          <div class="flex justify-center md:justify-between items-center text-sm">
            <div class="mr-3">
              <div v-show="notification.type == 'processing'">
                <img src="/assets/spinner-light.png" class="animate-spin" width="20">
              </div>
              <div v-show="notification.type == 'success'">
                <i class="fas fa-check-circle text-xl text-green-400"></i>
              </div>
              <div v-show="notification.type == 'failed'">
                <i class="fas fa-times-circle text-xl text-red-400"></i>
              </div>
            </div>
            <div>{{ notification.message }}</div>
          </div>
          <button @click="closeNotification(notification.id)" class="text-gray-400 hover:text-white ml-3">
            <i class="fas fa-times"></i>
          </button>
        </div>
    </TransitionGroup>
</template>

<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { ref, watch, inject, reactive, provide } from 'vue';

const page = usePage();
const activeNotifications = ref([]);
const notificationDuration = 7000;
const logoutModal = ref(false);
const loggingout = ref(false);
const loading = ref(false);
const hasChanges = inject('hasChanges');

const childData = reactive({
    value: null
});

// Provide it to children
provide("childData", childData);


function closeNotification(id) {
  activeNotifications.value = activeNotifications.value.filter(n => n.id !== id);
}

function showNotification(message, type) {
  if (!message) return;
  
  const notification = {
    id: Date.now(),
    message,
    type
  };
  
  activeNotifications.value.push(notification);
  
  setTimeout(() => {
    closeNotification(notification.id);
  }, notificationDuration);
}

watch(() => page.props.flash, (newFlash) => {
  if (newFlash.success) showNotification(newFlash.success, 'success');
  if (newFlash.updated) showNotification(newFlash.updated, 'success');
  if (newFlash.deleted) showNotification(newFlash.deleted, 'success');
  if (newFlash.uploaded) showNotification(newFlash.uploaded, 'success');
  if (newFlash.failed) showNotification(newFlash.failed, 'failed');
  if (newFlash.error) showNotification(newFlash.error, 'failed');
}, { deep: true });

router.on('start', () => {
  loading.value = true;
  document.body.style.overflow = 'hidden';
});

router.on('finish', () => {
  loading.value = false;
  document.body.style.overflow = 'auto';
});

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

// Make closeNotification available to template
defineExpose({
  closeNotification
});
</script>

<script>
    export default {
        data() {
            return {
                component: {
                    dashboard: [
                        "Dashboard/Dashboard-CHED-Progress",
                        "Dashboard/Dashboard-CHED-Overview",
                        "Dashboard/Dashboard-CHED-Analytics",
                        "Dashboard/Dashboard-HEI",
                        "Dashboard/Dashboard-CHED",
                        "Dashboard/CHED-ES-Dashboard",
                    ],
                    adminpanel: [
                        "Admin/User-List",
                        "Admin/User-Request",
                        "Admin/User-Inactive",
                        "Admin/CMO",
                        "Admin/CMO-Edit",
                        "Admin/CMO-Draft",
                        "Admin/CMO-View",
                        "Admin/EvaluationForm",
                        "Admin/HEI",
                        "Admin/HEI-Create",
                        "Admin/HEI-Edit",
                        "Admin/HEI-View",
                        "Admin/Program",
                        "Admin/Program-Create",
                        "Admin/Program-Edit",
                        "Admin/Discipline",
                        "Admin/Discipline-Create",
                        "Admin/Discipline-Edit",
                        "Admin/Profile-View",
                    ],
                    evaluation: [
                        "Evaluation/Evaluation-CHED",
                        "Evaluation/Evaluation-HEI",
                        "Evaluation/HEI-Evaluation-Edit",
                        "Evaluation/HEI-Evaluation-View",
                        "Evaluation/HEI-Evaluation-PH-Select",
                        "Evaluation/HEI-Evaluation-Select",
                        "Evaluation/HEI-Evaluation-Monitored",
                        "Evaluation/CHED-Evaluation-Select",
                        "Evaluation/CHED-Evaluation-Edit",
                        "Evaluation/CHED-Evaluation-View",
                        "Evaluation/CHED-Evaluation-Report-Create",
                        "Evaluation/CHED-Evaluation-Monitored",
                    ],
                    application: [
                        "Application/Application-Edit",
                    ],
                    myaccount: [
                        "Auth/Account",
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
                } else if (btn == "application") {
                    return this.component.application.includes(this.$page.component);
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

    props: {
        activeNotifications: {
            type: Array,
            required: true
        }
    },

        mounted() {
            window.addEventListener("resize", this.handleResize);

            if (window.innerWidth < 768) {
                this.closeSideBar = true;
            }
        },
    };
    
</script>

<style scoped>
    .notifications-enter-active,
    .notifications-leave-active {
    transition: all 0.3s ease;
    }

    .notifications-enter-from {
    opacity: 0;
    transform: translateY(30px);
    }

    .notifications-leave-to {
    opacity: 0;
    transform: translateY(30px);
    }

    .notifications-move {
    transition: transform 0.3s ease;
    }
</style>