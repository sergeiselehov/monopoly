<template>
    <SubHeader title="Баланс"/>
    <v-sheet
        class="pa-8"
        color="transparent"
        flex
    >
        <v-row justify="start"
               v-for="item in list"
               :key="item.id">
            <v-col xl="3"
                   md="3"
                   sm="3"
                   class="pa-3 ma-3"
            >
                <BalancesCard
                    title="Баланс"
                    :item="item"
                />
            </v-col>
            <v-col xl="3"
                   md="3"
                   sm="3"
                   class="pa-3 ma-3"
            >
                <PaymentsPieUser :userId="item.id" />
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <SummaryBalanceCard
                    class="ml-3 mr-5"
                    title="Суммарный баланс"
                />
            </v-col>
        </v-row>
        <v-row class="mt-5">
            <PaymentsPie/>
        </v-row>
    </v-sheet>
</template>

<script>
import LgToast from "./helpers/Toast.vue";
import Container from "./helpers/Container.vue";
import BalancesCard from "./shared/BalancesCard.vue";
import SubHeader from "./shared/SubHeader.vue";
import Table from "./shared/Table.vue";
import PieChart from "./shared/Graphs/PaymentsPie.vue";
import PaymentsPie from "./shared/Graphs/PaymentsPie.vue";
import SummaryBalanceCard from "./shared/SummaryBalanceCard.vue";
import PaymentsPieUser from "./shared/Graphs/PaymentsPieUser.vue";

export default {
    name: "Balance",
    components: {
        PaymentsPieUser,
        SummaryBalanceCard, PaymentsPie, PieChart, Table, SubHeader, BalancesCard, Container, LgToast},

    data: () => ({
        list: null,
    }),
    mounted() {
        axios.get('api/v1/balances-list').then(response => this.list = response.data.data);
    },
}
</script>

<style scoped>

</style>
