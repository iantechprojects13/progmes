<template>
    <div class="px-3 md:px-7 pt-4 md:text-gray-200 md:bg-blue-900 z-60">
        <h1 class="text-xl font-bold">Admin Panel</h1>
        <div class="mt-3 w-full z-40 text-gray-700 font-bold text-base md:pb-0">
            <div class="text-right">
                <button @click="toggleMenu"
                    class="flex flex-row  absolute top-20 right-3 justify-center items-center md:hidden w-14 h-10 rounded border border-gray-400 hover:border-gray-500 text-gray-400 hover:text-black hover:bg-gray-200">
                    <span v-if="!isMenuOpen">
                        <i class="fas fa-bars"></i>
                    </span>
                    <span v-else>
                        <i class="fas fa-close text-base"></i>
                    </span>
                </button>
            </div>
            <div ref="adminPanelContainer"
                class="relative transition-all duration-200 md:visible md:whitespace-nowrap h-0 md:h-auto md:border-none bg-stone-700 md:bg-blue-900 rounded md:rounded-none overflow-hidden"
                :class="[{ 'border-none invisible': !isMenuOpen }, { 'h-60': isMenuOpen }]">
                <div class="w-full sm:flex-row md:block md:text-gray-300 text-gray-200">
                    <Link :href="route('admin.users.list')" preserved-state>
                    <button
                        class="select-none h-12 md:w-auto md:px-5 w-full text-gray-200 md:hover:bg-blue-700 hover:bg-stone-600  rounded"
                        :class="{ 'md:text-white text-blue-500 md:border-b-blue-400 md:hover:border-blue-500 md:border-b-4 rounded-b-none md:hover:bg-blue-700': highlight('user') }">
                        Users
                    </button>
                    </Link>

                    <Link :href="route('admin.cmo.list')" preserved-state>
                    <button
                        class="select-none h-12 md:w-auto md:px-5 w-full text-gray-200 md:hover:bg-blue-700 hover:bg-stone-600  rounded"
                        :class="{ 'md:text-white text-blue-500 md:border-b-blue-400 md:hover:border-blue-500 md:border-b-4 rounded-b-none md:hover:bg-blue-700': highlight('cmo') }">
                        CMO
                    </button>
                    </Link>

                    <Link href="/admin/tool" preserved-state>
                    <button
                        class="select-none h-12 md:w-auto md:px-5 w-full text-gray-200 md:hover:bg-blue-700 hover:bg-stone-600  rounded"
                        :class="{ 'md:text-white text-blue-500 md:border-b-blue-400 md:hover:border-blue-500 md:border-b-4 rounded-b-none md:hover:bg-blue-700': highlight('form') }">
                        Compliance Tool
                    </button>
                    </Link>

                    <Link :href="route('admin.hei.list')">
                    <button
                        class="select-none h-12 md:w-auto md:px-5 w-full text-gray-200 md:hover:bg-blue-700 hover:bg-stone-600  rounded"
                        :class="{ 'md:text-white text-blue-500 md:border-b-blue-400 md:hover:border-blue-500 md:border-b-4 rounded-b-none md:hover:bg-blue-700': highlight('institution') }">
                        HEI
                    </button>
                    </Link>

                    <Link :href="route('admin.program.list')">
                    <button
                        class="select-none h-12 md:w-auto md:px-5 w-full text-gray-200 md:hover:bg-blue-700 hover:bg-stone-600  rounded"
                        :class="{ 'md:text-white text-blue-500 md:border-b-blue-400 md:hover:border-blue-500 md:border-b-4 rounded-b-none md:hover:bg-blue-700': highlight('program') }">
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
                    form: ['/admin/tool'],
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