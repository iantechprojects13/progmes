<template>
    <div class="w-full">
        <Pie id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full max-w-md mx-auto"/>
    </div>
</template>

<script>
    import { Pie } from 'vue-chartjs'
    import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, ArcElement)

    export default {
        name: 'PieChart',
        components: { Pie },
        data() {
            return {
                chartData: {
                    labels: this.labels,
                    datasets: [
                        {   
                            data: this.dataItem,
                            filter: this.filter,
                            tooltip: this.tooltip,
                            backgroundColor: this.color,
                        }
                    ]
                },
                chartOptions: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            onClick: null,
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(tooltipItem) {
                                    const indent = '\u00A0\u00A0\u00A0';
                                    const tooltip = tooltipItem.dataset.tooltip[tooltipItem.dataIndex];
                                    const filter = tooltipItem.dataset.filter;
                                    const sample = "In Q1 2024, 108 of 320 programs (33.75%) across HEIs were monitored, covering 15.56% of the total programs, falling below the __ (25%)target. With this low monitoring, immediate intervention is needed to increase the number of monitored programs and meet the threshold.";
                                    if (filter == 'HEI') {
                                        return [
                                            indent + 'In ' + tooltip[0] + ' quarter of ' + tooltip[1] + ',',
                                            indent + tooltip[2] + ' out of ' + tooltip[3] + ' programs (' + tooltip[4] + '%)',
                                            indent + 'at the ' + tooltip[5] + ' were monitored, ',
                                            indent + 'covering ' + tooltip[7] + '% of the total programs ('+ tooltip[8] +').',
                                        ]; 
                                    } else if (filter == 'Program') {
                                        return [
                                            indent + 'In ' + tooltip[0] + ' quarter of ' + tooltip[1] + ',',
                                            indent + tooltip[2] + ' out of ' + tooltip[3] + ' programs (' + tooltip[4] + '%)',
                                            indent + 'in ' + tooltip[6] + ' were monitored, ',
                                            indent + 'covering ' + tooltip[7] + '% of the total programs ('+ tooltip[8] +').',
                                        ];
                                    } else {
                                        return [
                                            indent + 'In ' + tooltip[0] + ' quarter of ' + tooltip[1] + ',',
                                            indent + tooltip[2] + ' out of ' + tooltip[3] + ' programs (' + tooltip[4] + '%)',
                                            indent + 'accross all HEIs were monitored, ',
                                            indent + 'covering ' + tooltip[7] + '% of the total programs ('+ tooltip[8] +').',
                                        ];
                                    }
                                    
                                    
                                    
                                }
                            },
                            backgroundColor: '#333',
                            titleColor: '#5dade2',
                            bodyColor: '#eee',
                            bodyFont: {
                                size: 12,
                            },
                        },
                    },
                    layout: {
                        padding: {
                            left: 10,
                            right: 10, 
                        }
                    },
                }
            }
        },
        props: [
            'title',
            'dataItem',
            'tooltip',
            'labels',
            'color',
            'programName',
            'filter',
        ],
    }
</script>
