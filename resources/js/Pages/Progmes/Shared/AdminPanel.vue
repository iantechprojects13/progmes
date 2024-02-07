<template>
    <h1 class="text-xl font-bold md:block inline">AdminPanel</h1>
    <div class="mt-3 mb-3 w-full md:sticky md:top-0 z-40 text-gray-700">
        <div class="relative transition-all duration-200 md:whitespace-nowrap border md:border-none">
            <div class="text-right absolute -top-12 right-0">
                <button @click="toggleMenu" class="md:hidden p-2 text-2xl text-gray-500">
                    <i v-if="isMenuOpen" class="fas fa-angle-up"></i>
                    <i v-else class="fas fa-angle-down"></i>
                </button>
            </div>
            <div class="bg-gray-100 text-sm sm:bg-white w-full sm:flex-row md:block rounded-sm md:border-b-2 border-gray-300"
                :class="{ 'hidden': !isMenuOpen, 'block': isMenuOpen }">
                <Link :href="route('admin.users.list')" preserved-state>
                <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('user') }">
                    Users
                </button>
                </Link>
                <Link :href="route('admin.cmo.list')">
                <button class="h-12 md:w-auto md:px-5  w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('cmo') }">
                    CMO
                </button>
                </Link>
                <Link href="/admin/form">
                <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('form') }">
                    Form
                </button>
                </Link>
                <Link href="/admin/higher-education-institutions">
                <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('institution') }">
                    HEI
                </button>
                </Link>
                <Link href="/admin/program">
                <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('program') }">
                    Program
                </button>
                </Link>
                <Link href="/admin/settings">
                <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                    :class="{ 'text-blue-500 font-bold md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('settings') }">
                    Settings
                </button>
                </Link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            admin_urls:
            {
                user: ['/admin/users'],
                cmo: ['/admin/CMOs'],
                form: ['/admin/form'],
                institution: ['/admin/higher-education-institutions'],
                program: ['/admin/program'],
                settings: ['/admin/settings'],
            },
            isMenuOpen: false,
        }
    },

    methods: {
        highlight(admin_btn) {
            if (admin_btn == 'user') {
                return this.admin_urls.user.includes(this.$page.url.slice(0, 12));
            } else if (admin_btn == 'cmo') {
                return this.admin_urls.cmo.includes(this.$page.url.slice(0, 11));
            } else if (admin_btn == 'request') {
                return this.admin_urls.request.includes(this.$page.url.slice(0, 14));
            } else if (admin_btn == 'institution') {
                return this.admin_urls.institution.includes(this.$page.url.slice(0, 36));
            } else if (admin_btn == 'program') {
                return this.admin_urls.program.includes(this.$page.url.slice(0, 14));
            } else if (admin_btn == 'form') {
                return this.admin_urls.form.includes(this.$page.url.slice(0, 11));
            } else if (admin_btn == 'settings') {
                return this.admin_urls.settings.includes(this.$page.url.slice(0, 15));
            }

        },
        toggleMenu() {
            this.isMenuOpen = !this.isMenuOpen;
        },
    },
}
</script>