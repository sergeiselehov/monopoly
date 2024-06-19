<template>
    <SubHeader title="Главная"/>

    <v-sheet
        class="pa-8"
        color="transparent"
    >
        <v-row justify="start">
            <BalanceCard
                class="ml-3 mr-5"
                title="Баланс P2P"
                :balance-rus="GET_MY_BALANCE_RUB"
            />
            <CreditBalanceCard
                class="ml-3 mr-5"
                title="Кредитный баланс"
                :balance="GET_MY_CREDIT_BALANCE_RUB"
            />
        </v-row>

        <v-row>
            <v-col
                lg="6"
                md="6"
                sm="6"
                cols="12"
            >
                <TransactionGraph/>
            </v-col>

            <v-col
                lg="6"
                md="6"
                sm="6"
                cols="12"
            >
                <PaymentsPie/>
            </v-col>

            <v-col
                lg="6"
                md="6"
                sm="6"
                cols="12"
                v-if="false"
            >
                <v-row>
                    <v-card title="Конверсия P2P" class="rounded-xl text-center h-100" color="common-light-gray">

                        <v-card-text>
                            <v-row>
                                <v-col cols="12" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="12" sm="12" cols="12">Час</v-col>

                            </v-row>
                        </v-card-text>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="12" sm="12" cols="12">Сутки</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="12" sm="12" cols="12">Общая</v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-row>
                <v-row>
                    <SmallLineCard/>
                    <v-card v-if="false" title="Конверсия C2C" class="rounded-xl text-center h-100"
                            color="common-light-gray">
                        <v-card-text>
                            <v-row>
                                <v-col cols="6" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="6" sm="12" cols="12">Час</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text>
                            <v-row>
                                <v-col cols="6" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="6" sm="12" cols="12">Сутки</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-text>
                            <v-row>
                                <v-col cols="6" style="height: 100px;">
                                    <Pie :data="data" :options="options"/>
                                </v-col>
                                <v-col xl="6" md="6" sm="12" cols="12">Общая</v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-row>

            </v-col>
        </v-row>

        <v-row v-if="!isManager">
                <TotalCountTransactions class="ml-3 mr-5" />

                <TotalCountPayments class="ml-3 mr-5" />

                <TotalCountDisputes class="ml-3 mr-5" />
        </v-row>

        <v-row>

            <v-col
                v-if="false"
                lg="3"
                md="6"
                sm="6"
                cols="12"
            >
                <v-card title="Доходы" class="rounded-xl text-center h-100 text-indigo-darken-4"
                        color="common-light-gray">
                    <v-card-text>
                        <v-row>
                            <v-col xl="6" md="6" sm="12" cols="12">Общий</v-col>
                            <v-col xl="6" md="6" sm="12" cols="12">
                                <v-select density="compact" :items="list_items"></v-select>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">100 000 000</v-col>
                        </v-row>
                    </v-card-text>

                    <v-card-text>
                        <v-row>
                            <v-col xl="6" md="6" sm="12" cols="12">Валовый</v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">100 000 000</v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                lg="6"
                md="6"
                sm="6"
                cols="12"
                v-if="false"
            >
                <WithdrawlsCard balance="100 000 000" withdrawls="100 000 000"/>
            </v-col>
            <v-col
                lg="6"
                md="6"
                sm="6"
                cols="12"
                v-if="false"
            >
                <IncomeCard/>
                <v-card v-if="false" title="Конверсия E-COM" class="rounded-xl text-center h-100"
                        color="common-light-gray">
                    <v-card-text>
                        <v-row>
                            <v-col cols="6" style="height: 100px;">
                                <Pie :data="data" :options="options"/>
                            </v-col>
                            <v-col xl="6" md="6" sm="12" cols="12">Час</v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-text>
                        <v-row>
                            <v-col cols="6" style="height: 100px;">
                                <Pie :data="data" :options="options"/>
                            </v-col>
                            <v-col xl="6" md="6" sm="12" cols="12">Сутки</v-col>
                        </v-row>
                    </v-card-text>
                    <v-card-text>
                        <v-row>
                            <v-col cols="6" style="height: 100px;">
                                <Pie :data="data" :options="options"/>
                            </v-col>
                            <v-col xl="6" md="6" sm="12" cols="12">Общая</v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col
                v-if="false"
                lg="3"
                md="6"
                sm="6"
                cols="12"
            >
                <v-card title="Вывод" class="rounded-xl text-center h-100" color="common-light-gray">
                    <v-card-text>
                        <v-row>
                            100 000 000
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col
                v-if="false"
                lg="3"
                md="6"
                sm="6"
                cols="12"
            >
                <v-card title="Спорные платежи" class="rounded-xl text-center h-100" color="common-light-gray">
                    <v-card-text>
                        <v-row>
                            <v-col xl="6" md="6" sm="12" cols="12">Количество</v-col>
                        </v-row>
                        <v-row>
                            0
                        </v-row>
                    </v-card-text>
                    <v-card-text>
                        <v-row>
                            <v-col xl="6" md="6" sm="12" cols="12">Баланс</v-col>
                        </v-row>
                        <v-row>
                            0
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-sheet>
</template>

<script>
import Toast from "./helpers/Toast.vue";
import {
    ArcElement,
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import {Pie} from 'vue-chartjs'
import SubHeader from "./shared/SubHeader.vue";
import TransactionGraph from "./shared/Graphs/TransactionGraph.vue";
import BalanceCard from "./shared/BalanceCard.vue";
import CreditBalanceCard from "./shared/CreditBalanceCard.vue";
import WithdrawlsCard from "./shared/WithdrawlsCard.vue";
import SmallLineCard from "./shared/SmallLineCard.vue";
import IncomeCard from "./shared/IncomeCard.vue";
import {mapActions, mapGetters} from "vuex";
import SummaryBalanceCard from "./shared/SummaryBalanceCard.vue";
import TotalCountTransactions from "./shared/TotalCountTransactions.vue";
import TotalCountPayments from "./shared/TotalCountPayments.vue";
import TotalCountDisputes from "./shared/TotalCountDisputes.vue";
import PaymentsPie from "./shared/Graphs/PaymentsPie.vue";

ChartJS.register(
    ArcElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

export default {
    name: "Home",
    components: {
        PaymentsPie,
        TotalCountDisputes,
        TotalCountPayments,
        TotalCountTransactions, SummaryBalanceCard, IncomeCard, SmallLineCard, WithdrawlsCard, BalanceCard, TransactionGraph, SubHeader, Toast, Pie, CreditBalanceCard},
    data() {
        return {
            labels: ['VueJs', 'EmberJs'],
            data: {

                datasets: [
                    {
                        backgroundColor: ['#41B883', '#E46651'],
                        data: [80, 20]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            },
            drawer: true,
            list_items: [
                'Сутки', '7 суток', '30 суток', '365 суток', 'За все время'
            ],
            isManager: false
        }
    },
    computed: {
        ...mapGetters([
            'GET_MY_BALANCE_RUB',
        ]),
        ...mapGetters([
            'GET_MY_CREDIT_BALANCE_RUB',
        ]),
    },
    methods: {
        ...mapActions([
            'GET_MY_BALANCE_FROM_API',
        ]),
        ...mapActions([
            'GET_MY_CREDIT_BALANCE_FROM_API',
        ]),
   },
   mounted() {
       this.isManager = this.$store.state.user.role_names.includes('Менеджер');
       this.GET_MY_BALANCE_FROM_API.call()
       this.GET_MY_CREDIT_BALANCE_FROM_API.call()
   }
}
</script>


<style scoped>

</style>
