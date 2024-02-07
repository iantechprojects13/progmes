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
        <div class="relative overflow-y-auto text-sm h-screen">
            <table class="w-full text-left overflow-scroll">
                <thead class="sticky top-0 uppercase">
                    <tr class="w-full shadow bg-white">
                        <th scope="col" class="px-2 py-4">
                            CHED Memorandum Order
                        </th>
                        <th scope="col" class="px-2 py-4">
                            Discipline
                        </th>
                        <th scope="col" class="px-2 py-4">
                            Program
                        </th>
                        <th scope="col" class="px-2 py-4">
                            Version
                        </th>
                        <th scope="col" class="px-3 py-2 text-right">
                            <i class="fas fa-ellipsis-v"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cmo in cmo_list.data" :key="cmo.id" class="border-b cursor-pointer hover:bg-gray-100">
                        <th scope="row" class="px-2 py-4 whitespace-nowrap flex items-center pr-10">
                            CMO No.{{ cmo.number }} Series of {{ cmo.series }}
                        </th>
                        <td class="px-1 py-4">
                            {{ cmo.disciplineId }}
                        </td>
                        <td class="px-1 py-4">
                            {{ cmo.programId }}
                        </td>
                        <td class="px-1 py-4">
                            {{ cmo.version }}
                        </td>
                        <td class="px-3 py-4 text-right">
                            <Link :href="route('admin.cmo.show', [cmo])" class="hover:bg-gray-200 p-3 rounded-full">
                            <i class="fas fa-angle-double-right"></i>
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div v-if="cmo_list.links.length > 3" class="h-10 text-gray-600 text-sm flex items-center justify-center mt-5">
        <Link v-for=" link  in  cmo_list.links " :key="link.id" :class="{ 'font-bold bg-blue-300': link.active }"
            class="border border-gray-300 h-full flex items-center px-3" :href="link.url"><span v-html="link.label"></span>
        </Link>
    </div>
</template>

<script setup>
defineProps([
    'cmo_list',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue'
export default {
    layout: Layout,
}
</script>

