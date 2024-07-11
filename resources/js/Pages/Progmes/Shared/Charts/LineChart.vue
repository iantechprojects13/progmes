<template>
    <div class="w-full">
        <Line id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full"/>
        <div class="w-full text-left mt-5 bg-blue-200 p-2 rounded-md text-gray-600">
            <i class="fas fa-info-circle text-sm mx-3"></i>The line graph compares the number of programs monitored in each quarter over the last three years.
        </div>
    </div>
</template>

<script>
    import { Line } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale)

    export default {
        name: 'LineChart',
        components: { Line },
        data() {
            return {
                chartData: {
                    labels: this.labels,
                    datasets: [
                        {   
                            data: this.lineChartDataItem,
                            backgroundColor: "rgba(0, 170, 255, 0.5)",
                            borderColor: "#0af",
                            fill: false,
                            tension: 0.3,
                        },
                    ]
                },
                chartOptions: {
                    responsive: true,
                    legends: false,
                    label: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5,
                            }
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10, 
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        },
                    },
                }
            }
        },
        props: [
            'title',
            'lineChartDataItem',
            'labels',
            'color',
        ],
    }
</script>
