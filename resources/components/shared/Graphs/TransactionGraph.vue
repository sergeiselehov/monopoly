<template>
    <v-card class="rounded-xl text-center h-100" color="sdgreen">
        <v-card-title class="d-flex">
            <h2 class="mr-5">График транзакций</h2>
            <v-select density="compact" :items="list_items" v-model="period" item-title="text" item-value="value"></v-select>
        </v-card-title>
        <v-card-text>
            <div v-if="values.length > 0" style="position: relative; height:40vh;">
                <Line :data="dataLine" :options="optionsLine" @change="console.log(dataLine)"/>
            </div>
        </v-card-text>
    </v-card>
</template>

<script>
import { ArcElement,
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend} from 'chart.js'
import { Line } from 'vue-chartjs'
ChartJS.register(
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Tooltip,
    Legend
)

export default {
    name: "TransactionsGraph",
    components: { Line },
    methods: {
        getData() {
            axios.get('api/v1/transactions/chart?period=' + this.period)
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
                        label: 'График транзакций',
                        backgroundColor: '#f87979',
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
                        label: 'График транзакций',
                        backgroundColor: '#f87979',
                        data: this.values, //[0, 800, 500, 2800, 1950, 750, 1500]
                    }
                ]
            },
            optionsLine: {
                plugins: {
                    datalabels: false,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                tension: 0.5,
                color: 'white',
                borderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 20,
                pointHoverBorderWidth: 7,
                fill: false,
                pointHoverBackgroundColor: '#FFFFFF',
                pointHoverBorderColor: '#090D1F',
                pointBackgroundColor: 'rgba(255, 255, 255, 0.0)',
                pointBorderColor: 'rgba(255, 255, 255, 0.0)',
                pointBorderWidth: 7,
                borderColor: 'rgba(255, 255, 255, 0.7)',
            },
            list_items: [
                { text: 'Сутки', value: 'day' },
                { text: '7 суток', value: 'week' },
                { text: '30 суток', value: 'month' },
                { text: '365 суток', value: 'year' },
                { text: 'За все время', value: 'all' }
            ]
        }
    },
}
</script>


<style scoped>

</style>
