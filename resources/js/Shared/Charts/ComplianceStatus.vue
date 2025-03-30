<template>
    <div class="h-52 w-full">
      <Doughnut id="my-chart-id" :options="chartOptions" :data="chartData" />
    </div>
  </template>
  
  <script>
  import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
  import { Doughnut } from 'vue-chartjs'
  import ChartDataLabels from 'chartjs-plugin-datalabels'
  
  ChartJS.register(ArcElement, Tooltip, Legend, ChartDataLabels)
  
  export default {
    name: 'DoughnutChart',
    components: { Doughnut },
    data() {
      return {
        chartData: {
          labels: this.labels,
          datasets: [
            {
              backgroundColor: this.color,
              data: this.dataItem,
              borderColor: ['#fff'],
              borderWidth: 2,
              rounded: true,
              circumference: 180,
              rotation: 270,
              borderRadius: 5,
            }
          ]
        },
        chartOptions: {
          responsive: true,
          maintainAspectRatio: false,
          label: false,
          plugins: {
            legend: {
              display: true,
              position: 'right',
            },
            // Configure datalabels plugin
            datalabels: {
              color: '#fff',
              font: {
                weight: 'bold',
                size: 12
              },
              formatter: (value, context) => {
                // You can format the value or show percentage
                const sum = context.dataset.data.reduce((a, b) => a + b, 0);
                const percentage = Math.round((value / sum) * 100) + '%';
                return percentage;
              },
              // Position the labels
              anchor: 'center',
              align: 'center',
              offset: 0,
              // You can conditionally display labels
              display: (context) => {
                // Only show labels for segments with values above a threshold
                const value = context.dataset.data[context.dataIndex];
                const sum = context.dataset.data.reduce((a, b) => a + b, 0);
                return (value / sum) > 0.005; // Only show if segment is > .5% of total
              },
              // You can add backgrounds to labels
              backgroundColor: (context) => {
                // Use a semi-transparent version of the segment color
                const value = context.dataset.data[context.dataIndex];
                if (value < 10) return 'rgba(0,0,0,0.5)';
                return 'rgba(0,0,0,0.7)';
              },
              borderRadius: 4,
              padding: {
                top: 4,
                bottom: 4,
                left: 6,
                right: 6
              },
              // For semi-circular charts, you might need to adjust the rotation
              rotation: (context) => {
                // This helps align labels in a semi-circular chart
                const angle = context.chart.getDatasetMeta(0).data[context.dataIndex].startAngle + 
                  (context.chart.getDatasetMeta(0).data[context.dataIndex].endAngle - 
                   context.chart.getDatasetMeta(0).data[context.dataIndex].startAngle) / 2;
                return angle > Math.PI ? angle - Math.PI : angle;
              }
            }
          },
          layout: {
            padding: {
              left: 10,
              right: 10,
              top: 10, // Added padding for labels
              bottom: 10,
            },
          },
          cutout: 10,
        }
      }
    },
    props: [
      'title',
      'dataItem',
      'color',
      'labels',
    ],
    // You can add methods to update chart styling dynamically
    methods: {
      updateLabelStyle(options) {
        // Example method to update label styling
        if (options.color) this.chartOptions.plugins.datalabels.color = options.color;
        if (options.fontSize) this.chartOptions.plugins.datalabels.font.size = options.fontSize;
        // You would need to update the chart instance after this
      }
    }
  }
  </script>