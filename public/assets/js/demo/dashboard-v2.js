$(document).on("change", ".select-rerata-jam-filter", function () {
	var filterny = $(this).val();
	$.ajax({
		url: baseURL + "dashboard/get_average_time_list/" + filterny,
		type: "GET",
		success: function (data) {
			console.log(data);
			var dt = JSON.parse(data);
			$(".wrapper-rerata-jam").html(dt);
		},
		error: function (data) {
			console.log("Error:", data);
		},
	});
});

if (document.querySelector(".app-with-light-sidebar")) {
	COLOR_BLUE =
		COLOR_INDIGO =
		COLOR_RED =
		COLOR_ORANGE =
		COLOR_LIME =
		COLOR_TEAL =
			"rgba(0,0,0,0.5)";
	COLOR_AQUA = COLOR_DARK_LIGHTER = COLOR_GREEN = "rgba(0,0,0,0.75)";
}

var handleVisitorsAreaChart = function () {
	const ctx = document.getElementById("visitors-line-chart").getContext("2d");

	// create list of day of this month and assign it as datany's labels
	const days = [];
	const today = new Date();
	const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
	for (let i = 1; i <= lastDay.getDate(); i++) {
		days.push(i);
	}


	var datany = {
		labels: days,
		datasets: [
			{
				label: "Masuk",
				data: [],
				backgroundColor: [COLOR_GREEN],
				fill: true,
			},
			{
				label: "Izin",
				data: [],
				backgroundColor: [COLOR_BLUE],
				fill: true,
			},
			{
				label: "Sakit",
				data: [],
				backgroundColor: [COLOR_YELLOW],
				fill: true,
			},
		],
	}

	$.ajax({
		url: baseURL + "dashboard/get_absensi_month",
		type: "GET",
		success: function(data){
			var dt = JSON.parse(data);
			console.log(dt);
			for(var i = 0; i < dt.jumlah_masuk.length; i++){
				datany.datasets[0].data.push(dt.jumlah_masuk[i]);
				datany.datasets[1].data.push(dt.jumlah_izin[i]);
				datany.datasets[2].data.push(dt.jumlah_sakit[i]);
			}
			
			const myChart = new Chart(ctx, {
				type: "line",
				data: datany,
				options: {
					responsive: true,
					plugins: {
						title: {
							display: false,
							text: (ctx) => "Perkembangan Absensi",
						},
						tooltip: {
							mode: "index",
							// get tooltip text
							callbacks: {
								title: function(tooltipItems, data) {
									//Return value for title
									return 'Tanggal: ' + tooltipItems[0].label;
								}
							}
						},
					},
					interaction: {
						mode: "nearest",
						axis: "x",
						intersect: false,
					},
					scales: {
						x: {
							title: {
								display: true,
								text: "Hari",
							},
						},
						y: {
							stacked: true,
							title: {
								display: true,
								text: "Tanggal",
							},
						},
					},
				},
			});

			return myChart;
		},
		error: function(data){
			console.log(
				"Error:",
				data
			)
		}
	})
};

var handleVisitorsDonutChart = function () {

	const ctx = document.getElementById("visitors-donut-chart").getContext("2d");

	var datany = {
		labels: ["Hadir", "Izin", "Sakit", "Alpa"],
		datasets: [
			{
				label: "# of Votes",
				data: [12, 19, 3, 5],
				backgroundColor: [
					COLOR_GREEN,
					COLOR_INDIGO,
					COLOR_YELLOW,
					COLOR_RED,
				]
			}
		],
	}

	$.ajax({
		url: baseURL + "dashboard/jumlah_status_absensi",
		type: "GET",
		success: function(data){
			var dt = JSON.parse(data);
			console.log(dt);
			
			datany.datasets[0].data = [dt.masuk[0], dt.izin[0], dt.sakit[0], dt.alpa[0]];
			
			$('.chart-legend-owo').html(`
				<li><i class="fa fa-circle fa-fw text-green fs-9px me-5px t-minus-1"></i> ${dt.masuk[1]} <span>Seluruhnya Masuk</span></li>
				<li><i class="fa fa-circle fa-fw text-red fs-9px me-5px t-minus-1"></i> ${dt.alpa[1]} <span>Seluruhnya Alpa</span></li>
				<li><i class="fa fa-circle fa-fw text-yellow fs-9px me-5px t-minus-1"></i> ${dt.sakit[1]} <span>Seluruhnya Sakit</span></li>
				<li><i class="fa fa-circle fa-fw text-indigo fs-9px me-5px t-minus-1"></i> ${dt.izin[1]} <span>Seluruhnya Izin</span></li>
			`);

			const myChart = new Chart(ctx, {
				type: 'pie',
				data: datany,
				options: {
					responsive: true,
					plugins: {
						legend: {
							display: false,
							position: 'top',
						},
						title: {
							display: false,
							text: 'Chart.js Pie Chart'
						}
					}
				},
			});
		
			return myChart;
			
		},
		error: function(data){
			console.log(
				"Error:",
				data
			)
		}
	})
};

var DashboardV2 = (function () {
	"use strict";
	return {
		init: function () {
			handleVisitorsAreaChart();
			handleVisitorsDonutChart();
		},
	};
})();
$(document).ready(function () {
	DashboardV2.init();
});
