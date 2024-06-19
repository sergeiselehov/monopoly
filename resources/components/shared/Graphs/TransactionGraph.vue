<template>
    <v-card class="rounded-xl text-center h-100" color="sdgreen">
        <v-card-title class="d-flex">
            <h2 class="mr-5">График транзакций</h2>
            <v-select density="compact" :items="list_items" ></v-select>
        </v-card-title>
        <v-card-text>
            <div style="position: relative; height:40vh;">
                <Line :data="dataLine" :options="optionsLine" />
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
    name: "Home",
    components: { Line },
    data() {
        return {
            dataLine : {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                        label: 'График транзакций',
                        backgroundColor: '#f87979',
                        data: [0, 800, 500, 2800, 1950, 750, 1500]
                    }
                ]
            },
            optionsLine: {
                plugins: {
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
            list_items:[
                'Сутки', '7 суток', '30 суток', '365 суток', 'За все время'
            ]
        }
    },
}
</script>


<style scoped>

</style>
