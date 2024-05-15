<template>
    <div class="w-full">
        <Bar id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full"/>
    </div>
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
                    labels: this.labels,
                    datasets: [
                        {   
                            data: this.dataItem,
                            backgroundColor: this.color,
                            barThickness: 65,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    indexAxis: this.orientation,
                    legends: false,
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
                            display: false,
                        },
                    },
                }
            }
        },
        props: [
            'title',
            'dataItem',
            'labels',
            'color',
            'orientation',
        ],
    }
</script>