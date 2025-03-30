<template>
    <div class="w-full h-72">
      <Line id="my-chart-id" :options="chartOptions" :data="chartData" class="w-full h-full" />
    </div>
  </template>
  
  <script>
  import { Line } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale } from 'chart.js'
  import ChartDataLabels from 'chartjs-plugin-datalabels'
  
  // Register Chart.js components
  ChartJS.register(
    Title, 
    Tooltip, 
    Legend, 
    LineElement, 
    PointElement, 
    CategoryScale, 
    LinearScale,
    ChartDataLabels // Register the datalabels plugin
  )
  
  export default {
    name: 'LineChart',
    components: { Line },
    data() {
      return {
        chartData: {
          labels: this.labels,
          datasets: [
            {   
              data: this.lineChartDataItem,
              backgroundColor: "rgba(0, 170, 255, 0.5)",
              borderColor: "#0af",
              fill: false,
              tension: 0.3,
            },
          ]
        },
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
          legends: false,
          label: false,
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
              top: 30, // Add some padding at the top for labels
            }
          },
          plugins: {
            legend: {
              display: false,
            },
            // Configure the datalabels plugin
            datalabels: {
              color: '#666',
              font: {
                weight: 'bold',
                size: 12
              },
              formatter: (value) => {
                return value; // You can format the value here
              },
              anchor: 'end',
              align: 'top',
              offset: 5,
              // You can conditionally display labels
              display: (context) => {
                return true; // Always show, or use a condition
                // Example: return context.dataIndex % 2 === 0; // Show every other label
              },
              // You can use the color prop if passed
              // color: this.color || '#666',
            }
          },
        }
      }
    },
    props: [
      'title',
      'lineChartDataItem',
      'labels',
      'color',
    ],
    methods: {
      // You can add methods to update label styling dynamically if needed
      updateLabelStyle(newColor) {
        this.chartOptions.plugins.datalabels.color = newColor;
        // You would need to update the chart instance after this
      }
    }
  }
  </script>