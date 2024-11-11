// Include this at the beginning of your script if you're using Chart.js v3+
Chart.register(ChartDataLabels);

// Set default font family and color (applicable for Chart.js v2 or below)
Chart.defaults.font.family = 'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
Chart.defaults.color = '#858796';

// Pie Chart Example with Data Labels
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',  // Doughnut is often better for showing labels
  data: {
    labels: ["House Cleaning", "Office Cleaning", "Deep Cleaning"],
    datasets: [{
      data: [55, 30, 15],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    plugins: {
      tooltip: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        caretPadding: 10,
      },
      legend: {
        display: false
      },
      datalabels: {
        color: '#fff', // Set label color to white
        font: {
          weight: 'bold'
        },
        formatter: (value, context) => {
          return context.chart.data.labels[context.dataIndex] + '\n' + value + '%';
        },
      }
    },
    cutout: '80%',
  },
  plugins: [ChartDataLabels]
});
