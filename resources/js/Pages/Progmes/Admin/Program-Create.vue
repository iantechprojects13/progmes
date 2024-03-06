<template>

    <Head title="New Program" />
    <AdminPanel />
    <content-container pageTitle="Create Program" hasBackButton="true">
        <template v-slot:back-button>
            <Link href="/admin/program">
            <button class="w-10 h-10 mr-2 rounded-full hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:main-content>
            <div class="p-5 w-full md:max-w-md md:shadow md:shadow-gray-600 rounded mx-auto md:my-10">
                <div>
                    <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                    <select v-model="form.discipline" type="text" id="discipline"
                        class="rounded text-sm border-gray-400 w-full my-0.5"
                        :class="[{ 'text-gray-500': form.discipline == null }, { 'text-gray-700': form.discipline }]">
                        <option :value="null">Select discipline</option>
                        <option v-for="item in discipline_list" :value="item.id" class="text-black">
                            {{ item.discipline }}
                        </option>
                    </select>
                    <FormErrorMessage :message="$page.props.errors.discipline" theme="dark" />
                </div>
                <div class="mt-3">
                    <label for="program" class="font-bold text-gray-600">Program</label>
                    <input v-model="form.program" type="text" id="program"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500"
                        placeholder="Program">
                    <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                </div>
                <!-- <div class="mt-3">
                    <label for="major" class="font-bold text-gray-600">Major</label>
                    <input v-model="form.major" type="text" id="major"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500" placeholder="Major">
                </div> -->
                <div class="text-right mt-5">
                    <button @click="submit"
                        class="rounded text-white bg-blue-500 hover:bg-blue-600 py-2 px-4">Create</button>
                </div>
            </div>
        </template>
    </content-container>
    <!-- <content-container placeholder="Search program">
        <template v-slot:channel>
            <Link :href="route('admin.program.list')">
            <button class="py-1 px-4 h-10 rounded-full">
                <i class="fas fa-arrow-left"></i>
            </button>
            </Link>
        </template>
        <template v-slot:actions>
            <div class="relative text-gray-600" @mouseleave="openCreateDropdown = false">
                <button class="px-3 border rounded h-10 text-white bg-blue-500 hover:bg-blue-600"
                    @click.prevent="openCreateDropdown = !openCreateDropdown">
                    <i class="fas fa-plus"></i>
                    <i class="fas fa-caret-down ml-3"></i></button>
                <div v-show="openCreateDropdown"
                    class="flex flex-col items-start absolute top-10 right-0 shadow border border-gray-300 bg-white rounded py-3 z-30 w-48"
                    @mouseover.prevent="openCreateDropdown = true">
                    <Link class="w-full" :href="route('admin.discipline.create')">
                    <button class="py-2 indent-2 flex flex-row w-full text-left hover:bg-gray-200 hover:text-black">
                        <div class="w-8">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>New discipline</div>
                    </button>
                    </Link>
                    <Link class="w-full" :href="route('admin.program.create')">
                    <button class="flex flex-row py-2 indent-2 w-full text-left hover:bg-gray-200 hover:text-black">
                        <div class="w-8">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>New program</div>
                    </button>
                    </Link>
                </div>
            </div>
        </template>
        <template v-slot:content>
            <div class="p-3 px-5 font-bold text-base">
                New Program
            </div>
            <div class="p-5 w-full md:max-w-md md:shadow md:shadow-gray-600 rounded mx-auto md:my-10">
                <div>
                    <label for="discipline" class="font-bold text-gray-600">Discipline</label>
                    <select v-model="form.discipline" type="text" id="discipline"
                        class="rounded text-sm border-gray-400 w-full my-0.5"
                        :class="[{ 'text-gray-500': form.discipline == null }, { 'text-gray-700': form.discipline }]">
                        <option :value="null">Select discipline</option>
                        <option v-for="item in discipline_list" :value="item.id" class="text-black">
                            {{ item.discipline }}
                        </option>
                    </select>
                    <FormErrorMessage :message="$page.props.errors.discipline" theme="dark" />
                </div>
                <div class="mt-3">
                    <label for="program" class="font-bold text-gray-600">Program</label>
                    <input v-model="form.program" type="text" id="program"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500" placeholder="Program">
                    <FormErrorMessage :message="$page.props.errors.program" theme="dark" />
                </div>
                <div class="mt-3">
                    <label for="major" class="font-bold text-gray-600">Major</label>
                    <input v-model="form.major" type="text" id="major"
                        class="rounded text-sm border-gray-400 w-full my-0.5 placeholder-gray-500" placeholder="Major">
                </div>
                <div class="text-right mt-5">
                    <button @click="submit"
                        class="rounded text-white bg-blue-500 hover:bg-blue-600 py-2 px-4">Create</button>
                </div>
            </div>
        </template>
    </content-container> -->
</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';

    const form = useForm({
        discipline: null,
        program: null,
        major: null,
    })

    function submit() {
        form.post('/admin/program/store', form);
    }

</script>

<script>
    import FormErrorMessage from '../Shared/FormErrorMessage.vue';
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
        data() {
            return {
                openCreateDropdown: false,
            };
        },
        components: {
            FormErrorMessage,
        },
        props: {
            discipline_list: Array,
        }
    }
</script>