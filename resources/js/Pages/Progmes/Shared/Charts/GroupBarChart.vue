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
                    labels: ["2023-2024", "2024-2025", "2025-2026"],
                    datasets: [
                        {   
                            label: "Q1",
                            data: [171, 412, 478],
                            backgroundColor: "#0af", // '', '#fc0', '#f33'
                            barThickness: 45,
                        },
                        {
                            label: "Q2",
                            data: [253, 266, 151 ],
                            backgroundColor: "#0f5",
                            barThickness: 45,
                        },
                        {
                            label: "Q3",
                            data: [412, 166, 251],
                            backgroundColor: "#fc0",
                            barThickness: 45,
                        },
                        {
                            label: "Q4",
                            data: [312, 346, 451],
                            backgroundColor: "#f33",
                            barThickness: 45,
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