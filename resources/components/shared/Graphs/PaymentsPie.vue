<script>
import { Chart, registerables } from 'chart.js';
import {Pie} from 'vue-chartjs';
import ChartDataLabels from 'chartjs-plugin-datalabels';

Chart.register(...registerables, ChartDataLabels);
export default {
    name: "PaymentsPie",
    components: { Pie },
methods: {
    getData() {
        axios.get('api/v1/transactions/conversion?period=' + this.period)
            .then(response => {
                this.labels = response.data.data.labels;
                this.values = response.data.data.values;
                this.updateChart();
            })
    },
    updateChart() {
        this.dataLine = {
            labels: this.labels,
            datasets: [
                {
                    label: 'Конверсия платежей',
                    data: this.values,
                }
            ]
        };
    },
},
watch: {
    period() {
        this.getData();
    }
},
mounted() {
    this.getData();
},
data() {
    return {
        period: 'day',
        values: [],
        labels: [],
        dataLine : {
            labels: this.labels,
            datasets: [
                {
                    label: 'Конверсия платежей',
                    data: [0, 800, 500, 2800, 1950, 750, 1500]
                }
            ]
        },
        optionsLine: {
            plugins: {
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = 0;
                        let dataArr = ctx.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        if (sum === 0) {
                            return '';
                        }
                        let percentage = (value  * 100 / sum).toFixed(2);
                        return percentage + '%';
                    },
                    color: '#fff',
                },
                legend: {
                    display: true
                }
            },
            responsive: true,
        },
        list_items: [
            { text: 'Час', value: 'hour' },
            { text: 'Сутки', value: 'day' },
            { text: 'Неделя', value: 'week' },
        ]
    }
},
}
</script>

<template>
<div>
    <v-card class="rounded-xl text-center h-100" color="sdgreen">
        <v-card-title class="d-flex">
            <h2 class="mr-5">Конверсия платежей</h2>
            <v-select density="compact" :items="list_items" v-model="period" item-title="text" item-value="value"></v-select>
        </v-card-title>
        <v-card-text>
            <div v-if="values.length > 0" style="position: relative; height:40vh;">
                <Pie :data="dataLine" :options="optionsLine" @change="console.log(dataLine)" />
            </div>
        </v-card-text>
    </v-card>
</div>

</template>

<style scoped>

</style>
