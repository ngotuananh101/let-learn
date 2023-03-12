<template>
    <canvas :id="id" class="chart-canvas" height="300"></canvas>
</template>

<script>
import Chart from "chart.js/auto";
export default {
    name: "StatsChart",
    props: {
        id: {
            type: String,
            default: "stats-chart",
        },
        chart: {
            type: Object,
            required: true,
            labels: String,
            datasets: {
                type: Array,
                label: Array,
                data: Array,
            },
        },
    },
    mounted() {
        var chart = document.getElementById(this.id).getContext("2d");

        var gradientStroke1 = chart.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, "rgba(203,12,159,0.2)");
        gradientStroke1.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke1.addColorStop(0, "rgba(203,12,159,0)"); //purple colors

        var gradientStroke2 = chart.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, "rgba(20,23,39,0.2)");
        gradientStroke2.addColorStop(0.2, "rgba(72,72,176,0.0)");
        gradientStroke2.addColorStop(0, "rgba(20,23,39,0)"); //purple colors

        // Line chart
        new Chart(chart, {
            type: "line",
            data: {
                labels: this.chart.labels,
                datasets: [
                    {
                        label: this.chart.datasets[0].label,
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 2,
                        pointBackgroundColor: "#4BB543 ",
                        borderColor: "#4BB543 ",
                        // eslint-disable-next-line no-dupe-keys
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        data: this.chart.datasets[0].data,
                        maxBarThickness: 6,
                    },
                    {
                        label: this.chart.datasets[1].label,
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 2,
                        pointBackgroundColor: "#f5365c",
                        borderColor: "#f5365c",
                        // eslint-disable-next-line no-dupe-keys
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        data: this.chart.datasets[1].data,
                        maxBarThickness: 6,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: "index",
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: "#9ca2b7",
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: true,
                            borderDash: [5, 5],
                        },
                        ticks: {
                            display: true,
                            color: "#9ca2b7",
                            padding: 10,
                        },
                    },
                },
            },
        });
    },
};
</script>
