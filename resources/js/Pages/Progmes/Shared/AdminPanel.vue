<template>
    <div class="px-4 md:px-7 pt-4 bg-white">
        <div class="flex flex-col md:flex-row justify-between">
            <h1 class="text-2xl text-gray-700 font-bold md:block inline">AdminPanel</h1>
            <div class="text-sm md:text-base text-gray-500">adminPanel > page > function</div>
        </div>
        <div class="mt-3 w-full z-40 text-gray-700 font-bold text-sm md:pb-0 md:border-b-2 border-gray-400"
            :class="{ 'pb-5': isMenuOpen }">
            <div class="text-right absolute top-6 right-5">
                <button @click="toggleMenu"
                    class="md:hidden w-10 h-10 rounded border border-gray-400 text-2xl text-gray-500 hover:text-black hover:bg-gray-200"
                    :class="{ 'bg-gray-300': isMenuOpen }">
                    <i v-if="isMenuOpen" class="fas fa-angle-up relative top-0.5"></i>
                    <i v-else class="fas fa-angle-down  relative top-0.5"></i>
                </button>
            </div>
            <div ref="adminPanelContainer"
                class="relative transition-all duration-200 md:visible md:whitespace-nowrap h-60 md:h-auto md:border-none bg-gray-300 overflow-hidden"
                :class="{ 'h-0 border-none invisible': !isMenuOpen }">
                <div class="md:bg-white w-full sm:flex-row md:block rounded-sm border-gray-500">
                    <Link :href="route('admin.users.list')" preserved-state>
                    <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('user') }">
                        Users
                    </button>
                    </Link>
                    <Link :href="route('admin.cmo.list')">
                    <button class="h-12 md:w-auto md:px-5  w-full hover:bg-stone-200 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('cmo') }">
                        CMO
                    </button>
                    </Link>
                    <Link href="/admin/form">
                    <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('form') }">
                        Form
                    </button>
                    </Link>
                    <Link href="/admin/higher-education-institutions">
                    <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('institution') }">
                        HEI
                    </button>
                    </Link>
                    <Link href="/admin/program">
                    <button class="h-12 md:w-auto md:px-5 w-full hover:bg-stone-200 rounded"
                        :class="{ 'text-blue-500 md:border-b-blue-500 md:border-b-4 rounded-none md:hover:bg-white': highlight('program') }">
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