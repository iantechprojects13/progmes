<template>
    <div class="w-full lg:h-80 h-52 ">
        <Bar id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full"/>
    </div>
</template>

<script>
    import { Bar } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
    import DataLabelsPlugin from 'chartjs-plugin-datalabels'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, DataLabelsPlugin)

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
                        hoverCursor: 'pointer',
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
                        top: 30
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    datalabels: {
                        display: true,
                        color: '#777',
                        anchor: 'end',
                        align: 'top',
                        formatter: (value) => value,
                        font: {
                            weight: 'normal',
                            size: 12
                        },
                        padding: {
                            top: 5
                        }
                    }
                },
                onClick: (event, elements) => {
                    this.handleChartClick(elements);
                },
                onHover: (event, chartElement) => {
                    event.native.target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                }
            }
        }
    },
    props: [
        'title',
        'dataItem',
        'labels',
        'color',
    ],
    methods: {
        handleChartClick(elements) {
            if (elements.length > 0) {
                const clickedIndex = elements[0].index;
                this.$emit('barClick', clickedIndex+1);
            }
        }
    }
}
</script>