<template>
    <div class="inline-block w-16">
        <div class="flex relative">
            <div class="w-full h-auto flex relative">
                <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData" />
                <div class="absolute top-6 left-2 w-12 text-xs text-center font-bold">
                    {{ percentage }}%
                </div>
            </div>
        </div>
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
                    labels: ['Complied', 'Not complied', 'Not applicable', 'No status'],
                    datasets: [
                        {
                            // labels: ['asdasd', 'ssss'],
                            backgroundColor: ['#06d', '#06d', '#06d', '#ccc'],
                            data: this.dataItem,
                            // borderColor: ['#bbb'],
                            borderWidth: 0,
                            // hoverBorderColor: '#999',
                            // hoverBorderWidth: 1,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: true,
                    label: false,
                    plugins: {
                        tooltip: {
                            enabled: false,
                        },
                        legend: {
                            display: false,
                        }
                    },
                    cutout: 20,
                    hover: false,

                }
            }
        },

        props: [
            'title',
            'dataItem',
            'percentage',
            'color',
        ],

    }
</script>