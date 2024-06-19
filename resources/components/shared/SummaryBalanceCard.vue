<template>
  <v-sheet v-if="balance" class="card" width="230" height="180">
      <v-img
          style="height: 24px; width: 24px; margin-bottom: 24px"
          src="/assets/img/logo-mini.png"
      />
      <h2 class="card-name">{{title}}</h2>
      <p>{{balance.summaryBalance.toLocaleString()}} руб.</p>
      <!--
      <p>КП: {{balance.summaryPaymentsCommission.toLocaleString()}} руб.</p>
      <p>КВ: {{balance.summaryWithdrawalPaymentsCommission.toLocaleString()}} руб.</p>
      -->
  </v-sheet>
</template>

<script>

export default {
    name: "SummaryBalanceCard",
    props: {
        title: {
            type: String,
            default: ''
        },
    },
    data: () => ({
        balance: null
    }),
    mounted(){
        axios.get('api/v1/summary-balance-info')
            .then(response => this.balance = response.data.data.balance)
    }
}
</script>

<style scoped>
.card {
    font-family: "Roboto Flex Variable", sans-serif;
    border-radius: 14px;
    padding: 18px;
        background: linear-gradient(38deg, #0c7046, #0c7046 65%, #013513 60%, #013513);

    P {
        font-weight: 200;
        line-height: 20px;
        letter-spacing: -0.16px;
    }
}

.card-name {
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    letter-spacing: -0.2px;
    margin-bottom: 12px;
}


</style>
