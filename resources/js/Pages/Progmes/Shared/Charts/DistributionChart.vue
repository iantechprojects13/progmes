<template>
    <div class="w-full">
        <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData" class="h-96"/>
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
                    labels: this.labels,
                    datasets: [
                        {
                            backgroundColor: this.color,
                            data: this.dataItem,
                            borderColor: ['#fff'],
                            rounded: true,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    label: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'right',
                        },
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 0,
                            bottom: 0,
                        },
                    },
                    cutout: 10,

                }
            }
        },

        props: [
            'title',
            'dataItem',
            'color',
            'labels',
        ],

    }
</script>