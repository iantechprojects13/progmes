<template>
  <div>
    <progress-bar></progress-bar>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';

export default {
  extends: Bar,
  props: {
    dataItem: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      chartData: {
        labels: ['Complied', 'Not complied', 'Not applicable', 'No status'],
        datasets: [
          {
            label: 'Data Label',
            backgroundColor: ['#06d', '#06d', '#06d', '#ccc'],
            data: [1,2,3,4],
          },
        ],
      },
      chartOptions: {
        responsive: false,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            enabled: false,
          },
        },
        scales: {
          x: {
            grid: {
              display: false,
            },
          },
          y: {
            grid: {
              display: true,
            },
            ticks: {
              beginAtZero: true,
            },
          },
        },
      },
    };
  },
};
</script>
