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
                            label: this.labels[0],
                            data: this.thisYear,
                            filter: this.filter,
                            growthRate: this.growthRate.thisYear,
                            borderColor: "#85C1E9",
                            fill: false,
                            tension: 0.3,
                        },
                        {
                            label: this.labels[1],
                            data: this.lastYear,
                            filter: this.filter,
                            growthRate: this.growthRate.lastYear,
                            borderColor: "#F7DC6F",
                            fill: false,
                            tension: 0.3,
                        },
                        {   
                            label: this.labels[2],
                            data: this.twoYearsAgo,
                            filter: this.filter,
                            growthRate: this.growthRate.twoYearsAgo,
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
                            onClick: null,
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
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function (tooltipItem) {
                                    
                                    const indent = '\u00A0\u00A0\u00A0';
                                    const datasetLabel = tooltipItem.dataset.label;
                                    let growthRate = tooltipItem.dataset.growthRate[tooltipItem.dataIndex];
                                    const dataValue = tooltipItem.raw;
                                    const quarter = tooltipItem.label;
                                    let mark = "";

                                    growthRate = parseFloat(growthRate);

                                    if (growthRate > 0 && tooltipItem.dataIndex > 0) {
                                        mark = "Growth rate was improved by " + Math.abs(growthRate).toFixed(2) + "% compared to the previous quarter of the same year.";
                                    } else if (growthRate < 0  && tooltipItem.dataIndex > 0) {
                                        mark = "Growth rate was declined by " + Math.abs(growthRate).toFixed(2) + "% compared to the previous quarter of the same year.";
                                    } else if (growthRate == 0 && tooltipItem.dataIndex > 0) {
                                        mark = "";
                                    }
                                    
                                    
                                    
                                    // Now use your logic to generate the custom tooltip text based on `filter`
                                    const filter = tooltipItem.dataset.filter;
                                    
                                    if (filter === 'HEI') {
                                        return [
                                            indent + `In ${quarter} of ${datasetLabel},`,
                                            indent + `${dataValue} programs were monitored in this HEI.`,
                                            indent + mark,
                                        ];
                                    } else if (filter === 'Program') {
                                        return [
                                            indent + `In ${quarter} of ${datasetLabel},`,
                                            indent + `${dataValue} programs were monitored in this program.`,
                                            indent + mark,
                                        ];
                                    } else {
                                        return [
                                            indent + `In ${quarter} of ${datasetLabel},`,
                                            indent + `${dataValue} programs were monitored across all HEIs.`,
                                            indent + mark,
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
                        }

                    },
                }
            }
        },
    props: [
        'thisYear',
        'lastYear',
        'twoYearsAgo',
        'labels',
        'filter',
        'growthRate',
        'isCurrentYear',
        'currentQuarter',
    ],
        
    }
</script>
