<template>
    <div class="w-full lg:h-96 h-52">
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
                            barThickness: 45,
                            borderColor: '#fff',
                            borderWidth: 2,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legends: false,
                    label: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                beginAtZero: true,
                                stepSize: 5,
                            },
                            grid: {
                                display: false,
                            },
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
        ],
    }
</script>