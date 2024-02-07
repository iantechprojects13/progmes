<template>
    <Head title="Dashboard" />
    <div class="text-xl font-bold mb-5">
        Dashboard
    </div>
    <div class="w-full">
        <div class="font-bold text-gray-500">
            Compliance Status
        </div>
        <div>
            <DoughnutChart title="Complied" :dataItem="complied" :percentage="compliedPercentage" :items="complied[0]"
                :totalItem="totalItem" color="blue" />
            <DoughnutChart title="Not complied" :dataItem="notComplied" :percentage="notCompliedPercentage"
                :items="notComplied[0]" :totalItem="totalItem" color="red" />
        </div>
    </div>
    <div v-if="this.$page.props.flash.success">
        {{ this.$page.props.flash.success }}
    </div>
</template>

<script setup>
defineProps([
    'complied',
    'notComplied',
    'compliedPercentage',
    'notCompliedPercentage',
    'totalItem',
]);
</script>

<script>
import Layout from '../Shared/Layout.vue';

export default {
    layout: Layout,
}
</script>