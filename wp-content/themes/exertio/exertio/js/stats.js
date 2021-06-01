! function (r) {
  "use strict";
  if ("undefined" != typeof chart_strings && null !== chart_strings && r("#bar-chart").length > 0) {
    var t = r("#bar-chart").get(0).getContext("2d"),
      a = "",
      s = "";
    a = r.parseJSON(chart_strings.labelz), s = r.parseJSON(chart_strings.stats_data);
    var e = {
        labels: a,
        datasets: [{
          label: !1,
          backgroundColor: chart_strings.chart_bg,
          borderColor: chart_strings.chart_border,
          borderWidth: 1,
          data: s
        }]
      },
      i = {
        scaleBeginAtZero: !0,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        scaleGridLineWidth: 1,
        barShowStroke: !0,
        barStrokeWidth: 2,
        barValueSpacing: 1,
        barDatasetSpacing: 1,
        legend: {
          display: !1
        },
        tooltips: {
          enabled: !0,
          mode: "x-axis",
          cornerRadius: 1
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: !0
            }
          }]
        }
      };
    new Chart(t, {
      type: chart_strings.chart_type,
      data: e,
      options: i
    })
  }
}(jQuery);
