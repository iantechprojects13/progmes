<template>
    <Head title="CHED Memorandum Order List" />
    <AdminPanel />
    <div class="border-2 rounded-lg bg-white">
        <div
            class="w-full flex md:flex-row flex-col justify-between text-sm items-center py-2 px-3 border-b-2 rounded-t-lg md:z-50 md:bg-white">
            <div class="flex items-center justify-between w-full pr-2">
                <div class="flex items-center my-2">
                    <div class="mr-0.5">
                        <button class="hover:bg-gray-500 p-2 rounded bg-gray-600 text-white w-20">List</button>
                    </div>
                    <div class="mr-0.5 flex">
                        <Link :href="route('admin.cmo.create')">
                        <button class="hover:bg-gray-200 p-2 rounded border w-20">Create</button>
                        </Link>
                    </div>
                </div>
            </div>
            <div class="w-52">
                <form action="#">
                    <input type="search" id="search" placeholder="Search CMO"
                        class="rounded text-sm w-full border-2 border-gray-400">
                </form>
            </div>
        </div>
        <div class="px-2 py-4 text-sm font-bold border-b-2">
            <Link :href="route('admin.cmo.list')" class="hover:bg-gray-200 rounded-full p-2">
            <i class="fas fa-arrow-left mr-2"></i>
            {{ cmo }}
            </Link>
        </div>
        <div class="relative overflow-y-auto text-sm h-screen">
            <table>
                <tr>
                    <th>Item No</th>
                    <th>Area</th>
                    <th>Minimum Requirement</th>
                </tr>
                <tr v-for="(item, index) in criteria" :key="item.id" class="border-b-2">
                    <td class="p-2">{{ index + 1 }}</td>
                    <td class="p-2">{{ item.area }}</td>
                    <td class="p-2">{{ item.minimumRequirement }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps([
    'criteria',
    'cmo',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>

