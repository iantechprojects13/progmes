<template>
    <div class="w-full">
        <Line id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full"/>
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
                    labels: [
                        "Jan", "Feb", "Mar", "Apr",
                        "May", "Jun", "Jul", "Aug",
                        "Sep", "Oct", "Nov", "Dec"
                    ],
                    datasets: [
                        {   
                            data: this.lineChartDataItem,
                            backgroundColor: "rgba(0, 170, 255, 0.5)",
                            borderColor: "#0af",
                            fill: false,
                            tension: 0.2,
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
