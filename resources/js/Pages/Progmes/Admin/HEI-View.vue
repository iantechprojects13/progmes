<template>
    <Head title="HEI List" />
    <AdminPanel />
    <div class="border-2 rounded-lg">
        <div
            class="w-full flex md:flex-row flex-col justify-between text-sm items-center py-2 px-3 border-b-2 rounded-t-lg">
            <div class="flex items-center justify-between w-full pr-2">
                <div class="flex items-center my-2">
                    <div class="mr-0.5">
                        <button class="hover:bg-gray-500 p-2 w-20 rounded bg-gray-600 text-white">List</button>
                    </div>
                    <div class="mr-0.5">
                        <Link :href="route('admin.hei.create')">
                        <button class="hover:bg-gray-200 p-2 w-20 rounded border">Create</button>
                        </Link>
                    </div>
                </div>
                <div>
                    Button
                </div>
            </div>
            <div class="w-52">
                <form action="#">
                    <input type="search" id="search" placeholder="Search institution"
                        class="rounded-lg text-sm w-full border-gray-300">
                </form>
            </div>
        </div>
        <div class="px-2 py-4 text-sm font-bold border-b-2">
            <Link :href="route('admin.hei.list')" class="hover:bg-gray-200 rounded-full p-2">
            <i class="fas fa-arrow-left mr-2"></i> {{
                institution.name }}</Link>
        </div>
        <div class="p-3">
            <div class="flex-row">
                <div class="text-lg font-bold">{{ institution.name }}</div>
                <div class="text-sm">{{ institution.address }}</div>
                <div class="text-sm">{{ institution.cityOrMunicipality }}</div>
                <div class="text-sm">{{ institution.cityOrProvince }}</div>
            </div><br>
            <div>
                <div class="font-bold">Programs Offered</div>
                <div>asdds</div>
            </div>
            <div>

            </div>
        </div>
    </div>
    <div v-if="$page.props.flash.success">
        <Notification :message="this.$page.props.flash.success" />
    </div>
</template>

<script setup>


const props = defineProps({
    'institution': Object,
});


</script>

<script>
import Layout from '../Shared/Layout.vue';
export default {
    layout: Layout,
}
</script>

