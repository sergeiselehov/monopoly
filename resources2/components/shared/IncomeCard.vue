<template>
    <v-card class="rounded-xl">
        <v-text-field>
            <DoughnutChart v-bind="doughnutChartProps" />
        </v-text-field>
    </v-card>
</template>

<script lang='ts'>
import { computed, ref } from "vue";
import { DoughnutChart, useDoughnutChart } from "vue-chart-3";
import { Chart, ChartData, ChartOptions, registerables } from "chart.js";

Chart.register(...registerables);

export default {
    name: "IncomeCard",
    components: { DoughnutChart },
    setup() {
        const dataValues = ref([30, 40, 60, 70]);

        const testData = computed<ChartData<"doughnut">>(() => ({
            labels: ["Subscription", "Investing", "Groceries", "Online Shop"],
            datasets: [
                {
                    data: dataValues.value,
                    backgroundColor: [
                        "#42BD53",
                        "#FFE066",
                        "#006AFF",
                        "#FF6666",
                    ],
                },
            ],
        }));

        const options = computed<ChartOptions<"doughnut">>(() => ({
            plugins: {
                legend: {
                    labels: {
                      color: 'white',
                        pointStyle: "circle",
                      font: {
                          size: 20,
                          weight: 'bold',
                      },
                      usePointStyle: true,
                    },
                    position: "left",
                },
                title: {
                    display: true,
                    text: "Доходы",
                    color: 'white',
                    align: "start",
                    font: {
                        size: 32,
                        weight: 'bold',
                    },
                },
            },
            elements: {
                arc: {
                    borderWidth: 0,
                }
            },
            cutout: '85%',
        }));

        const { doughnutChartProps, doughnutChartRef } = useDoughnutChart({
            chartData: testData,
            options,
        });

        return {
            testData,
            options,
            doughnutChartRef,
            doughnutChartProps,
        };
    },
};
</script>

<style>
</style>
