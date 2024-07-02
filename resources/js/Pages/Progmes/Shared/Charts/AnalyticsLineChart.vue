<template>
    <div class="w-full px-5">
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
                    labels: [
                        "1st Quarter", "2nd Quarter", "3rd Quarter", "4th Quarter"
                    ],
                    datasets: [
                        {   
                            label: "This Year",
                            // data: this.lineChartDataItem1,
                            data: [231, 312, 123, 412],
                            borderColor: "#85C1E9",
                            fill: false,
                            tension: 0.3,
                        },
                        {   
                            label: "A Year Ago",
                            // data: this.lineChartDataItem2,
                            data: [198, 331, 161, 371],
                            borderColor: "#F7DC6F",
                            fill: false,
                            tension: 0.3,
                        },
                        {   
                            label: "Two Years Ago",
                            // data: this.lineChartDataItem3,
                            data: [313, 210, 319, 232],
                            borderColor: "#EC7063",
                            fill: false,
                            tension: 0.3,
                        },
                    ]
                },
                chartOptions: {
                    responsive: true,
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
                            left: 10,
                            right: 10,
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    size: 12,
                                },
                            }
                        },
                    },
                }
            }
        },
        props: [
            'lineChartDataItem1',
            'lineChartDataItem2',
            'lineChartDataItem3',
        ],
    }
</script>
