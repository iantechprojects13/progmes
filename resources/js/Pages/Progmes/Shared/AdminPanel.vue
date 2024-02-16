<template>
    <div class="px-3 md:px-7 pt-4 bg-white text-gray-600">
        <h1 class="text-2xl font-bold">AdminPanel</h1>
        <div class="mt-3 w-full z-40 text-gray-700 font-bold text-sm md:pb-0 md:border-b-2 border-gray-400">
            <div class="text-right">
                <button @click="toggleMenu"
                    class="flex flex-row  absolute top-3 right-3 justify-center items-center md:hidden w-auto px-5 h-10 rounded border border-gray-400 text-gray-500 hover:text-black hover:bg-gray-100">
                    <span v-if="!isMenuOpen">
                        <i class="fas fa-bars"></i>
                    </span>
                    <span v-else>
                        <i class="fas fa-close text-base"></i>
                    </span>
                </button>
            </div>
            <div ref="adminPanelContainer"
                class="relative transition-all duration-200 md:visible md:whitespace-nowrap h-0 md:h-auto md:border-none bg-stone-700 border border-gray-400 rounded md:rounded-none overflow-hidden"
                :class="[{ 'border-none invisible': !isMenuOpen }, { 'h-60': isMenuOpen }]">
                <div
                    class="md:bg-white w-full sm:flex-row md:block rounded-sm border-gray-500 md:text-gray-600 text-gray-200">
                    <Link :href="route('admin.users.list')" preserved-state>
                    <button
                        class="h-12 md:w-auto md:px-5 w-full md:hover:bg-stone-200 hover:bg-stone-600 md:hover:text-black rounded "
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white md:hover:text-blue-500': highlight('user') }">
                        Users
                    </button>
                    </Link>
                    <Link :href="route('admin.cmo.list')">
                    <button class="h-12 md:w-auto md:px-5 w-full md:hover:bg-stone-200 hover:bg-stone-600 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white md:hover:text-blue-500': highlight('cmo') }">
                        CMO
                    </button>
                    </Link>
                    <Link href="/admin/form">
                    <button
                        class="h-12 md:w-auto md:px-5 w-full md:hover:bg-stone-200 hover:bg-stone-600 md:hover:text-black rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white md:hover:text-blue-500': highlight('form') }">
                        Form
                    </button>
                    </Link>
                    <Link href="/admin/higher-education-institutions">
                    <button v-show="$page.props.auth.user.role == 'Super Admin'"
                        class="h-12 md:w-auto md:px-5 w-full md:hover:bg-stone-200 hover:bg-stone-600 md:hover:text-black rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white md:hover:text-blue-500': highlight('institution') }">
                        HEI
                    </button>
                    </Link>
                    <Link href="/admin/program">
                    <button v-show="$page.props.auth.user.role == 'Super Admin'"
                        class="h-12 md:w-auto md:px-5 w-full md:hover:bg-stone-200 hover:bg-stone-600 md:hover:text-black rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white md:hover:text-blue-500': highlight('program') }">
                        Program
                    </button>
                    </Link>
                </div>
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

        handleResize() {
            if (window.innerWidth > 768) {
                this.isMenuOpen = false;
            }
        },
    },
    mounted() {
        window.addEventListener('resize', this.handleResize);
    },
}
</script>