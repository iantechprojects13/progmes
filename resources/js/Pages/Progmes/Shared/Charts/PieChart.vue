<template>
    <div class="w-full">
        <Pie id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full"/>
    </div>
</template>

<script>
    import { Pie } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, ArcElement)

    export default {
        name: 'PieChart',
        components: { Pie },
        data() {
            return {
                chartData: {
                    labels: this.labels,
                    datasets: [
                        {   
                            data: this.dataItem,
                            backgroundColor: this.color,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                        },
                        tooltip: {
                            enabled: true,
                        },
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10, 
                        }
                    },
                }
            }
        },
        props: [
            'title',
            'dataItem',
            'labels',
            'color',
        ],
    }
</script>
