<template>
    <div class="inline-block w-20">
        <div class="font-bold text-center mb-2">
            Progress
        </div>
        <div class="flex relative">
            <div class="w-full h-20 flex relative">
                <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData" />
                <div class="absolute top-7 left-5 w-12 text-center font-bold">
                    {{ percentage }}%
                </div>
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
                    labels: ['asdasd', 'aasss'],
                    datasets: [
                        {
                            // labels: ['asdasd', 'ssss'],
                            backgroundColor: ['#06d', '#ccc'],
                            data: this.dataItem,
                            // borderColor: ['#bbb'],
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
            'color',
        ],

    }
</script>