<template>
    <v-sheet class="card" style="width: 100%; height: 100%">
        <v-img
            style="height: 24px; width: 24px; margin-bottom: 24px"
            src="/assets/img/logo-mini.png"
        />
        <h2 class="card-name">{{ item.name }}</h2>
        <p>Баланс: {{ item.balance }} руб.</p>
        <p>Кредит: {{ item.credit_balance }} руб.</p>
        <br>
        <h2 class="card-name">
            Транзакции
            <small style="font-size: 55%">За сутки</small>
        </h2>
        <p>Всего: {{ item.transactions_last_day }}</p>
        <p>Успешные: {{ item.successful_transactions_last_day }}</p>
        <br>
        <h2 class="card-name">
            Выплаты
            <small style="font-size: 55%">За сутки</small>
        </h2>
        <p>Всего: {{ item.withdrawals_last_day }}</p>
        <p>Успешные: {{ item.successful_withdrawals_last_day }}</p>
    </v-sheet>
</template>

<script>

export default {
    name: "BalancesCard",
    props: {
        title: {
            type: String,
            default: ''
        },
    item: Object,
    },
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
