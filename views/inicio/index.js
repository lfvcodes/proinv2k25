import {
  loadComponents,
  response,
  number_format,
  getMonthsUntilCurrent,
} from "@util";

loadComponents();

function getTasaToday() {
  response("tasa/", { endpoint: "getTasaToday" }).then((data) => {
    if (data.status == 200) {
      if (data.result === `Actualizar Tasa`) {
        let param = btoa("true");
        window.location.href = `../tasa/?actualizar=${param}`;
      }
    }
  });
}

window.loadCharts = function () {
  response("inventario/", { endpoint: "getDashboard" }).then((data) => {
    if (data.status == 200) {
      // $rs = JSON.parse($rs);
      let totalcxc = number_format(data.result["total_cxc"]);
      let totalcxp = number_format(data.result["total_cxp"]);
      $("#tcxc").text(`$ ${totalcxc}`);
      $("#tcxp").text(`$ ${totalcxp}`);

      // Area Chart Example
      let ctx = document.getElementById("myAreaChart");
      let actualMonths = getMonthsUntilCurrent();
      let myLineChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: actualMonths,
          datasets: [
            {
              label: "Compras",
              lineTension: 0.3,
              backgroundColor: "rgba(78, 115, 223, 0.05)",
              borderColor: "rgba(78, 115, 223, 1)",
              pointRadius: 3,
              pointBackgroundColor: "rgba(78, 115, 223, 1)",
              pointBorderColor: "rgba(78, 115, 223, 1)",
              pointHoverRadius: 3,
              pointHoverBackgroundColor: "#5a5c69",
              pointHoverBorderColor: "#5a5c69",
              pointHitRadius: 10,
              pointBorderWidth: 2,
              data: data.result["compras_por_mes"],
            },
            {
              label: "Ventas",
              lineTension: 0.3,
              backgroundColor: "rgba(27, 200, 138, 0.05)",
              borderColor: "#1BC88A",
              pointRadius: 3,
              pointBackgroundColor: "#1BC88A",
              pointBorderColor: "#1BC88A",
              pointHoverRadius: 3,
              pointHoverBackgroundColor: "#1BC88A",
              pointHoverBorderColor: "#1BC88A",
              pointHitRadius: 10,
              pointBorderWidth: 2,
              data: data.result["ventas_por_mes"],
            },
          ],
        },
        options: {
          maintainAspectRatio: false,
          layout: {
            padding: 5,
          },
          scales: {
            xAxes: [
              {
                time: {
                  unit: "date",
                },
                gridLines: {
                  display: false,
                  drawBorder: false,
                },
                ticks: {
                  maxTicksLimit: 7,
                },
              },
            ],
            yAxes: [
              {
                ticks: {
                  maxTicksLimit: 5,
                  padding: 10,
                  // Include a dollar sign in the ticks
                  callback: function (value, index, values) {
                    return number_format(value);
                  },
                },
                gridLines: {
                  color: "rgb(234, 236, 244)",
                  zeroLineColor: "rgb(234, 236, 244)",
                  drawBorder: false,
                  borderDash: [2],
                  zeroLineBorderDash: [2],
                },
              },
            ],
          },
          legend: {
            display: false,
          },
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            titleMarginBottom: 10,
            titleFontColor: "#6e707e",
            titleFontSize: 14,
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            intersect: false,
            mode: "index",
            caretPadding: 10,
            callbacks: {
              label: function (tooltipItem, chart) {
                let datasetLabel =
                  chart.datasets[tooltipItem.datasetIndex].label || "";
                return datasetLabel + ": " + number_format(tooltipItem.yLabel);
              },
            },
          },
        },
      });

      // Pie Chart Example
      let ctx2 = document.getElementById("myPieChart");
      let myPieChart = new Chart(ctx2, {
        type: "doughnut",
        data: {
          labels: ["Entrada de Articulos", "Salida de Articulos"],
          datasets: [
            {
              data: [data.result["total_compras"], data.result["total_ventas"]],
              backgroundColor: ["#2637bd", "#1BC88A"],
              hoverBackgroundColor: ["#5a5c69", "#5a5c69"],
              hoverBorderColor: "rgba(234, 236, 244, 1)",
            },
          ],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: "#dddfeb",
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false,
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
};

$(function () {
  getTasaToday();
  loadCharts();
});
