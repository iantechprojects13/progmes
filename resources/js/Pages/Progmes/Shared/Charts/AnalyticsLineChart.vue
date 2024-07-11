<template>
    <div class="w-full lg:h-96 h-52">
        <Line id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full h-auto"/>
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
                        "1st Quarter", "2nd Quarter", "3rd Quarter", "4th Quarter"
                    ],
                    datasets: [
                        {   
                            label: "This Year",
                            data: this.thisYear,
                            borderColor: "#85C1E9",
                            fill: false,
                            tension: 0.3,
                        },
                        {   
                            label: "A Year Ago",
                            data: this.lastYear,
                            borderColor: "#F7DC6F",
                            fill: false,
                            tension: 0.3,
                        },
                        {   
                            label: "Two Years Ago",
                            data: this.twoYearsAgo,
                            borderColor: "#EC7063",
                            fill: false,
                            tension: 0.3,
                        },
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legends: true,
                    label: true,
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
                            // left: 10,
                            // right: 10,
                        },
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 12,
                                    padding: 10,
                                },
                            }
                        },
                    },
                }
            }
        },
        props: [
            'thisYear',
            'lastYear',
            'twoYearsAgo'
        ],
    }
</script>
