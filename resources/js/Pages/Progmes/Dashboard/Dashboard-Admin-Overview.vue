<template>
    <Head title="Dashboard"/>
    <page-title title="Dashboard"/>
    <div class="mx-3 md:mx-8 mt-5 border-b-2 border-gray-400">
        <Link href="/admin/dashboard/progress" class="mr-7">
            <button class="h-10 text-gray-700 hover:text-black  ">
                Progress
            </button>
        </Link>
        <Link href="/admin/dashboard/overview">
            <button class="h-10 border-b-2 border-blue-500 text-blue-500 font-bold">
                Overview
            </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col xl:flex-row justify-between">
        <div class="flex flex-col md:flex-row w-full lg:mr-10 mr-0">
            <div class="border border-gray-500 bg-white px-5 py-3 w-full mt-3 mr-10 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ user }}</span>
                    <br>Users
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-user"></i>
                </div>
            </div>
            <div class="border border-gray-500 bg-white px-5 py-3 w-full mt-3 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ institution }}</span>
                    <br>HEIs
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-institution"></i>
                </div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row w-full lg:mt-3">
           <div class="border border-gray-500 bg-white px-5 py-3 w-full mt-3 lg:mt-0 mr-10 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ program }}</span>
                    <br>Programs
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <div class="border border-gray-500 bg-white px-5 py-3 w-full mt-3 lg:mt-0 rounded flex flex-row justify-between items-center">
                <div>
                    <span class="font-bold text-lg mb-5">{{ discipline }}</span>
                    <br>Discipline
                    <br><span class="text-blue-500">Overall</span>
                </div>
                <div class="text-3xl text-blue-500">
                    <i class="fas fa-book"></i>
                </div>
            </div> 
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 flex flex-col xl:flex-row justify-between">
        <div class="w-full lg:w-1/2 border border-gray-400 bg-white rounded mt-3 lg:mt-0">
            <div class="p-3 border-b border-gray-400"><b>User-type Distribution</b></div>
            <div class="flex items-center text-center w-full h-36">
                <compliance-status :dataItem="usertype" :color="['#06d', '#09f']" :labels="['CHED', 'HEI']">

                </compliance-status>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps([
    'user',
    'institution',
    'program',
    'discipline',
    'usertype',
]);


</script>


<script>
    import Layout from '../Shared/Layout.vue';

    export default {
        layout: Layout,
    }
</script>