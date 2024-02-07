<template>
    <div
        class="inline-block p-3 pt-1 w-72 border border-gray-300 mr-4 my-2 text-gray-500 hover:text-gray-800 bg-gray-50 shadow-lg">
        <div class="h-10 font-bold">
            {{ title }}
        </div>
        <div class="flex relative mb-2">
            <div class="w-full min-w-10 h-20 inline-block relative">
                <div class="absolute top-7 left-14 w-12 text-center font-bold">
                    {{ percentage }}%
                </div>
                <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData" />
            </div>
            <div class="w-full flex items-center text-xl font-bold text-gray-400">
                {{ items }} of {{ totalItem }}
            </div>
        </div>
    </div>
</template>


<script>
import { Chart as ChartJS, ArcElement, Tooltip, Legend, Chart } from 'chart.js'
import { Doughnut } from 'vue-chartjs'
ChartJS.register(ArcElement, Tooltip, Legend)
export default {
    name: 'DoughnutChart',
    components: { Doughnut },
    data() {
        return {
            chartData: {
                datasets: [
                    {
                        backgroundColor: [this.color, '#bbb'],
                        data: this.dataItem,
                        // borderColor: ['#ddd'],
                        borderWidth: 0,
                        // hoverBorderColor: '#999',
                        // hoverBorderWidth: 1,
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                label: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                cutout: 30,
                hover: false,

            }
        }
    },

    props: [
        'title',
        'dataItem',
        'percentage',
        'items',
        'totalItem',
        'color',
    ],

}
</script>