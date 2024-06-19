<template>

</template>

<script>
import {mapActions, mapGetters} from "vuex";

export default {
    name: "Conversion",
    data: () => ({
    }),
    computed: {
        ...mapGetters([
            'GET_MY_TRANSACTIONS'
        ]),
    },
    mounted() {
        this.getData(1)
    },
    methods: {
        ...mapActions([
            'GET_TRANSACTIONS_FROM_API'
        ]),
        async getData(page) {
            await this.GET_TRANSACTIONS_FROM_API(page);
        },
        createMerchant() {
            this.field.merchant = {
                id: null,
                name: null,
            }
            this.dialog.merchant.create = true
        },
    },
}
</script>

<style scoped>

</style>
