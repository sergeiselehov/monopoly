<template xmlns="http://www.w3.org/1999/html">
    <v-sheet class="card" width="230" height="150" v-if="count.success || count.failed">
        <v-img style="height: 24px; width: 24px; margin-bottom: 24px" src="/assets/img/logo-mini.png"/>
        <h2 class="card-name">
            Диспуты
            <small style="font-size: 55%">За все время</small>
        </h2>
        <p>Успешные: {{ count.success }}</p>
        <p>Неуспешные: {{ count.failed }}</p>
    </v-sheet>
</template>

<script>

export default {
    name: "TotalCountDisputes",
    data: () => ({
        count: {},
    }),
    mounted() {
        axios.get('api/v1/disputes/total-count').then(response => this.count = response.data.data);
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
