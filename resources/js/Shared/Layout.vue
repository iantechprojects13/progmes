<template>
    <!-- Body -->
    <div class="flex flex-col md:flex-row h-full bg-gray-900">
        <!-- Navigation -->
        <div
            class="fixed z-90 top-0 w-full h-full bg-black bg-opacity-70 md:hidden"
            :class="{ hidden: closeSideBar }"
            @click="toggleSideBar()"
        ></div>
        <div
            class="h-full absolute top-0 w-64 left-0 md:relative z-90 transition-all duration-200 bg-opacity-20"
            :class="{
                'md:w-0 -translate-x-64 md:sticky md:top-0': closeSideBar,
            }"
            @click.self="toggleSideBar()"
        >
            <div class="md:block md:w-64">
                <div
                    class="flex-col h-screen w-64 bg-gray-900 md:w-64 pt-1 text-gray-200 md:block top-0 left-0 fixed"
                >
                    <div
                        class="flex flex-col justify-between h-full md:sticky md:top-0"
                    >
                        <div class="flex-shrink-0">
                            <div
                                class="p-2 px-4 my-2 flex flex-row items-center select-none"
                            >
                                <img
                                    src="/assets/ched-logo.png"
                                    width="60"
                                    class="mr-3"
                                />
                                <div class="text-2xl">ProgMES</div>
                            </div>
                            <div>
                                <div>
                                    <div
                                        class="select-none w-full h-16 p-2 px-4 mt-3 relative flex items-center justify-start border-y-2 border-gray-800"
                                    >
                                        <div class="inline-block mr-3">
                                            <img
                                                :src="
                                                    $page.props.auth.user.avatar
                                                "
                                                width="40"
                                                class="rounded-full"
                                            />
                                        </div>
                                        <div
                                            class="inline-block overflow-hidden"
                                        >
                                            <div
                                                class="text-sm whitespace-nowrap overflow-hidden overflow-ellipsis"
                                            
                                                :title="$page.props.auth.user.name">
                                                {{ $page.props.auth.user.name }}
                                            </div>
                                            <p
                                                class="text-xs whitespace-nowrap overflow-hidden overflow-ellipsis"
                                            
                                                :title="$page.props.auth.user.role == 'Vice-President for Academic Affairs' ? 'VPAA/Deans of Multiple Discipline/Program' : $page.props.auth.user.role"
                                                >
                                                <span v-if="$page.props.auth.user.role == 'Vice-President for Academic Affairs'">
                                                    VPAA/Dean of Multiple Discipline/Program
                                                </span>
                                                <span v-else>
                                                    {{ $page.props.auth.user.role }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="my-4 px-3" @click="mobileSideBar">
                                <div class="mt-3 text-xs mb-1 text-gray-300 ">
                                    DASHBOARD
                                </div>
                                <Link
                                    :href="route('dashboard')"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('dashboard')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800',
                                        { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"
                                        />
                                    </svg>
                                    Dashboard
                                </Link>

                                <div class="mt-5 text-xs mb-1 text-gray-300" v-if="$page.props.auth.user.type == 'CHED'">
                                    ADMIN
                                </div>
                                <Link
                                    :href="route('admin.users.list')"
                                    v-if="$page.props.auth.user.type == 'CHED'"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('adminpanel')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800', { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                        />
                                    </svg>
                                    Admin Panel
                                </Link>

                                <div class="mt-5 text-xs mb-1 text-gray-300">
                                    <span v-show="$page.props.auth.user.type == 'HEI'">SELF-</span>EVALUATION
                                </div>
                                <Link v-show="$page.props.auth.user.role != 'Librarian'"
                                    :href="route('evaluation')"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('evaluation')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800', { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                <svg 
                                    class="w-5 h-5 mr-3" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"
                                    />
                                </svg>
                                    Program Monitoring
                                </Link>

                                <Link v-if="showLibraryNavBtn"
                                    :href="route('library.evaluation')"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('library')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800', { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                <svg 
                                    class="w-5 h-5 mr-3" 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round" 
                                        stroke-width="2" 
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                    />
                                </svg>
                                    Library Compliance
                                </Link>

                                <Link v-show="false"
                                    :href="route('application')"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('application')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800', { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Program Application
                                </Link>
                                <div class="mt-5 text-xs mb-1 text-gray-300">
                                    ACCOUNT
                                </div>
                                <Link
                                    :href="
                                        '/myaccount/' + $page.props.auth.user.id
                                    "
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg "
                                    :class="[
                                        highlight('myaccount')
                                            ? 'bg-blue-600 text-white'
                                            : 'text-gray-300 hover:bg-gray-800', { 'pointer-events-none opacity-50': hasUnsavedChanges.value }
                                    ]"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                    My Account
                                </Link>

                                <button
                                    @click="toggleLogoutModal"
                                    :disabled="hasUnsavedChanges.value"
                                    class="flex items-center select-none w-full px-3 py-2 rounded-lg  text-gray-300"
                                    :class="[{ 'opacity-50': hasUnsavedChanges.value }, { 'hover:text-red-500 hover:bg-gray-800': !hasUnsavedChanges.value }]"
                                >
                                    <svg
                                        class="w-5 h-5 mr-3"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    Sign out
                                </button>
                            </div>
                            <!-- <button
                                @click="clearDarkMode()"
                                class="flex items-center select-none w-full px-3 py-2 rounded-lg text-gray-300 hover:text-blue-500 hover:bg-gray-800"
                            >
                                {{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}
                            </button> -->
                        </div>

                        <!-- Sidebar Toggle -->
                        <div
                            class="absolute top-1/2 text-gray-400 hidden md:block text-md">
                            <div
                                class="w-8 h-8 flex items-center text-white rounded-full justify-center font-bold cursor-pointer layout-tooltip hover:text-xl transition-all duration-200"
                                :class="[{
                                    'bg-gray-400 text-black hover:bg-gray-700 hover:text-white left-60 hover:left-62': closeSideBar
                                },
                                 {'bg-gray-700 left-60': !closeSideBar }]"
                                :data-tooltip="
                                    closeSideBar
                                        ? 'Open sidebar'
                                        : 'Close sidebar'
                                "
                                @click="
                                    toggleSideBar();
                                    autoHideSideBar(!closeSideBar);
                                "
                            >
                                <i
                                    :class="
                                        closeSideBar
                                            ? 'fas fa-angle-right'
                                            : 'fas fa-angle-left'
                                    "
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="w-full h-auto relative bg-white md:pt-0 pb-5 overflow-x-hidden">
            <div
                class="md:hidden sticky top-0 w-full bg-gradient-to-r from-indigo-800 to-blue-600 text-white flex justify-between text-center h-16 items-center shadow-md z-80 px-4"
            >
                <div class="cursor-pointer" @click="toggleSideBar">
                    <i class="fas fa-bars text-xl"></i>
                </div>
                <div class="flex flex-row items-center">
                    <div class="text-sm mr-2">
                        Hi, {{ $page.props.auth.user.name }}
                    </div>
                    <dropdown-option position="right">
                        <template v-slot:button>
                            <img
                                :src="$page.props.auth.user.avatar"
                                width="35"
                                class="border-2 bg-white rounded-full cursor-pointer"
                            />
                        </template>
                        <template v-slot:options>
                            <div class="w-52">
                                <Link
                                    :href="
                                        '/myaccount/' + $page.props.auth.user.id
                                    "
                                >
                                    <button
                                        class="block py-2 indent-3 hover:bg-gray-200 w-full text-left"
                                    >
                                        <i class="fas fa-user mr-3"></i>My
                                        Account
                                    </button>
                                </Link>
                                <button
                                    @click="toggleLogoutModal"
                                    :disable="loggingout"
                                    class="block py-2 indent-3 hover:bg-gray-200 w-full text-left"
                                >
                                    <i class="fas fa-sign-out mr-3"></i>Sign out
                                </button>
                            </div>
                        </template>
                    </dropdown-option>
                </div>
            </div>
            <div class="max-w-full">
                <slot />
            </div>
        </div>
    </div>
    <div>
        <confirmation
            :showModal="logoutModal"
            @close="toggleLogoutModal"
            title="Confirm Exit"
            width="md"
            height="short"
        >
            <template v-slot:message>
                <div>Are you sure you want to sign out?</div>
            </template>
            <template v-slot:buttons>
                <button
                    @click="logout"
                    class="select-none text-white bg-red-500 hover:bg-red-600 px-3 py-2.5 rounded-lg text-sm font-medium border"
                >Sign out
                </button>
            </template>
        </confirmation>
    </div>
    
    <!-- Loading -->
    <loading-screen v-if="loading" />

    <!-- Notifications -->
    <TransitionGroup
        name="notifications"
        tag="div"
        class="fixed bottom-0 right-0 z-[995] p-4 space-y-2 select-none"
    >
        <div
            v-for="notification in activeNotifications"
            :key="notification.id"
            class="bg-stone-800 text-white p-5 w-full md:max-w-md flex justify-between items-center rounded-md"
        >
            <div
                class="flex justify-center md:justify-between items-center text-sm"
            >
                <div class="mr-3">
                    <div v-show="notification.type == 'processing'">
                        <img
                            src="/assets/spinner-light.png"
                            class="animate-spin"
                            width="20"
                        />
                    </div>
                    <div v-show="notification.type == 'success'">
                        <i
                            class="fas fa-check-circle text-xl text-green-400"
                        ></i>
                    </div>
                    <div v-show="notification.type == 'failed'">
                        <i class="fas fa-times-circle text-xl text-red-400"></i>
                    </div>
                </div>
                <div>{{ notification.message }}</div>
            </div>
            <button
                @click="closeNotification(notification.id)"
                class="text-gray-400 hover:text-white ml-3"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
    </TransitionGroup>

    <!-- warning if user has unsaved changes -->
    <div
        v-if="hasUnsavedChanges.value"
        class="w-auto h-auto bg-yellow-400 fixed z-[100] bottom-2 left-2 p-2 rounded flex items-center"
    >
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span>Changes unsaved. You can press Ctrl + S to save.</span>
    </div>

    <!-- Saving changes -->
    <!-- <div
        v-if="isUpdating.value"
        class="w-auto h-auto bg-yellow-400 fixed z-[100] bottom-2 left-2 p-2 rounded flex items-center"
    >
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <span>Saving</span>
    </div> -->

    
</template>

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { ref, watch, reactive, provide, onMounted, onBeforeUnmount } from "vue";

const page = usePage();
const activeNotifications = ref([]);
const notificationDuration = 7000;
const logoutModal = ref(false);
const loggingout = ref(false);
const loading = ref(false);
const isNavigatingWithHistory = ref(false);
const isDarkMode = ref('');

const hasUnsavedChanges = reactive({
    value: null,
});

const isUpdating = reactive({
    value: null,
});

const toggleDarkMode = () => {
    const isDark = document.documentElement.classList.toggle('dark');
    isDarkMode.value = isDark;
    localStorage.setItem('pms_theme', isDark ? 'dark' : 'light');
};

const clearDarkMode = () => {
  document.documentElement.classList.remove('dark');
  localStorage.removeItem('pms_theme');
};

onMounted(() => {
    window.addEventListener('popstate', handlePopState);

    if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
  }
});

onBeforeUnmount(() => {
  window.removeEventListener('popstate', handlePopState);
});

function handlePopState() {
  isNavigatingWithHistory.value = true;
  // Reset after a short delay to handle future non-popstate navigations
  setTimeout(() => {
    isNavigatingWithHistory.value = false;
  }, 100);
}

// Provide it to children.value
provide("hasUnsavedChanges", hasUnsavedChanges);
provide("isUpdating", isUpdating);

function closeNotification(id) {
    activeNotifications.value = activeNotifications.value.filter(
        (n) => n.id !== id
    );
}

function showNotification(message, type) {
    if (!message) return;

    const notification = {
        id: Date.now(),
        message,
        type,
    };

    activeNotifications.value.push(notification);

    setTimeout(() => {
        closeNotification(notification.id);
    }, notificationDuration);
}

watch(
  () => page.props.flash,
  (newFlash) => {
    // Skip showing notifications if we're navigating with browser history
    if (isNavigatingWithHistory.value) return;
    
    if (newFlash.success) showNotification(newFlash.success, "success");
    if (newFlash.updated) showNotification(newFlash.updated, "success");
    if (newFlash.deleted) showNotification(newFlash.deleted, "success");
    if (newFlash.uploaded) showNotification(newFlash.uploaded, "success");
    if (newFlash.failed) showNotification(newFlash.failed, "failed");
    if (newFlash.error) showNotification(newFlash.error, "failed");
  },
  { deep: true }
);

router.on("start", () => {
    loading.value = true;
    document.body.style.overflow = "hidden";
});

router.on("finish", () => {
    loading.value = false;
    document.body.style.overflow = "auto";
});

function toggleLogoutModal() {
    logoutModal.value = !logoutModal.value;
}

function logout() {
    router.post("/logout", loggingout.value, {
        onStart: () => {
            loggingout.value = true;
        },
        onFinish: () => {
            loggingout.value = false;
        },
    });
}

// Make closeNotification available to template
defineExpose({
    closeNotification,
});


const showLibraryNavBtn = () => {
    return page.props.auth.user.role === 'Librarian' || 
           page.props.auth.user.role === 'Program Head' || 
           page.props.auth.user.role === 'Program Coordinator' || 
           page.props.auth.user.role === 'Vice-President for Academic Affairs' || 
           page.props.auth.user.type === 'CHED';
};

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
                    "Admin/user/User-List",
                    "Admin/user/User-Request",
                    "Admin/user/User-Inactive",
                    "Admin/cmo/CMO-List",
                    "Admin/cmo/CMO-Edit",
                    "Admin/cmo/CMO-Draft",
                    "Admin/cmo/CMO-View",
                    "Admin/tool/ComplianceTool-List",
                    "Admin/library/Library-Evaluation-List",
                    "Admin/library/Library-CMO-View",
                    "Admin/library/Library-CMO-Edit",
                    "Admin/hei/HEI-List",
                    "Admin/hei/HEI-Create",
                    "Admin/hei/HEI-Edit",
                    "Admin/hei/HEI-View",
                    "Admin/program/Program-List",
                    "Admin/program/Program-Create",
                    "Admin/program/Program-Edit",
                    "Admin/discipline/Discipline-List",
                    "Admin/discipline/Discipline-Create",
                    "Admin/discipline/Discipline-Edit",
                    "Admin/Profile-View",
                    "Admin/Settings",
                    "Admin/Program-Assignment",
                ],
                evaluation: [
                    "Evaluation/HEI-Evaluation-Edit",
                    "Evaluation/HEI-Evaluation-View",
                    "Evaluation/HEI-Evaluation-PH-List",
                    "Evaluation/HEI-Evaluation-PC-List",
                    "Evaluation/HEI-Evaluation-List",
                    "Evaluation/HEI-Evaluation-Monitored",
                    "Evaluation/CHED-Evaluation-List",
                    "Evaluation/CHED-Evaluation-Edit",
                    "Evaluation/CHED-Evaluation-View",
                    "Evaluation/CHED-Evaluation-Report",
                    "Evaluation/CHED-Evaluation-Monitored",
                ],
                library: [
                    "Evaluation/HEI-Evaluation-Library-List",
                    "Evaluation/HEI-Evaluation-Library-Edit",
                    "Evaluation/HEI-Evaluation-Library-View",
                    "Evaluation/CHED-Evaluation-Library-List",
                    "Evaluation/CHED-Evaluation-Library-Edit",
                    "Evaluation/CHED-Evaluation-Library-View",
                    "Evaluation/CHED-Evaluation-Library-Report",
                    "Evaluation/CHED-Evaluation-Library-Monitored",
                ],
                application: [
                    "Application/CHED-Application-List",
                    "Application/HEI-Application-List",
                    "Application/HEI-Application-Edit",
                    "Application/CHED-Application-Edit",
                    "Application/GPR-View",
                    "Application/GPR-Edit",
                ],
                myaccount: ["Auth/Account"],
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
            } else if (btn == "library") {
                return this.component.library.includes(this.$page.component);
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
            required: true,
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
