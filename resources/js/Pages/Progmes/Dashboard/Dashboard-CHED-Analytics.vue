<template>
    <Head title="Analytics"/>
    <page-title title="Dashboard" />
    <div class="mx-3 md:mx-8 mt-5 border-b-2 border-gray-400">
        <Link href="/ched/dashboard/progress" class="mr-7">
            <button class="select-none h-10 text-gray-700 hover:text-black">
                Progress
            </button>
        </Link>
        <Link href="/ched/dashboard/analytics" class="mr-7">
            <button class="select-none h-10 border-b-2 border-blue-500 text-blue-500 font-bold">
                Analytics
            </button>
        </Link>
        <Link href="/ched/dashboard/overview">
            <button class="select-none h-10 text-gray-700 hover:text-black">
                Overview
            </button>
        </Link>
    </div>
    <div class="mx-3 md:mx-8 mt-5 border border-gray-400 rounded p-3 bg-white flex flex-col lg:flex-row justify-between items-center">
        <div class="mb-3 lg:mb-0 w-full pr-5">
            (Selected program - criteria)
        </div>
        <div class="flex flex-col lg:flex-row w-full lg:w-auto">
            <div class="flex flex-col lg:flex-row items-center lg:whitespace-nowrap w-full lg:w-auto">
                <select id="">
                    <option value="">Select program</option>
                </select>
            </div>
        </div>
    </div>
    <div class="mx-3 md:mx-8 mt-5 border border-gray-400 rounded p-3">
        <bar-chart :dataItem="[]" :labels="['Complied', 'Not complied', 'Not applicable']" :color="['#09f', '#06f', '#03f']"></bar-chart>
    </div>
</template>

<script>
import BarChart from '../Shared/Charts/BarChart.vue';
import Layout from '../Shared/Layout.vue';

export default {
  components: { BarChart },
    layout: Layout,
}
</script>