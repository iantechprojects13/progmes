<template>
    <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>

<script>
    import { Bar } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default {
        name: 'BarChart',
        components: { Bar },
        data() {
            return {
                chartData: {
                    labels: ['Complied'],
                    datasets: [
                        { data: [40] }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    indexAxis: 'y', // Set the index axis to 'y' for horizontal bar chart
                    elements: {
                        bar: {
                            borderWidth: 5,
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                        },
                        y: {
                            beginAtZero: true,
                        },
                    },
                }
            }
        }
    }
</script>