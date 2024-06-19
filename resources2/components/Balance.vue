<template>
    <v-dialog width="500" v-model="dialog.withdrawal.create">
        <v-card class="rounded-xl" color="common-light-gray">
            <v-toolbar density="compact" color="common-blue">
                <v-toolbar-title v-text="'Добавление'"/>
                <v-spacer/>
                <v-toolbar-items>
                    <v-btn @click="dialog.withdrawal.create=false" variant="text">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text class="py-2">
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Кошелек (TRX-20)"
                    v-model="field.withdrawal.wallet"
                    :rules="rules"
                    :error-messages="error['wallet']"
                    clearable
                />
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Сумма в рублях"
                    :rules="rules"
                    v-model="field.withdrawal.amount"
                    class="mt-2"
                    :error-messages="error['amount']"
                    clearable
                />
            </v-card-text>
            <v-card-actions>
                <v-btn
                    :loading="loading.withdrawal.create"
                    :disabled="loading.withdrawal.create"
                    @click="storeWithdrawal"
                    variant="flat"
                    color="success"
                >Добавить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <SubHeader title="Баланс">
        <template #right>
            <v-btn
                height="48"
                variant="flat"
                text="Вывод средств"
                color="action-btn"
                @click="createWithdrawal">
                <template #prepend>
                    <v-icon icon="mdi-currency-usd"/>
                </template>
            </v-btn>
        </template>
    </SubHeader>
    <v-sheet
        class="pa-8"
        color="transparent"
    >
        <v-row justify="space-between">
            <BalanceCard
                title="Баланс"
                :balance-rus="this.GET_MY_BALANCE_RUB"
                :balance-u-s-d="this.GET_MY_BALANCE_USDT"
            />
        </v-row>
    </v-sheet>
    <Table
        v-if="
        this.GET_MY_WITHDRAWALS !== undefined &&
        this.GET_MY_WITHDRAWALS !== null &&
        this.GET_MY_WITHDRAWALS.length > 0
"
        title="История выплат"
        :table-header="tableHeader"
        :tableData="this.GET_MY_WITHDRAWALS"
    >
        <template #action-btn v-if="false">
            <v-btn
                text="экспорт в файл"
                prepend-icon="mdi-file"
                variant="outlined">
            </v-btn>
        </template>
    </Table>
</template>

<script>

import axios from "axios";
import LgToast from "./helpers/Toast.vue";
import Container from "./helpers/Container.vue";
import {mapActions, mapGetters} from 'vuex';
import BalanceCard from "./shared/BalanceCard.vue";
import SubHeader from "./shared/SubHeader.vue";
import Table from "./shared/Table.vue";

export default {
    name: "withdrawals",
    components: {Table, SubHeader, BalanceCard, Container, LgToast},

    data: () => ({
        rules: [
            value => {
                if (value) return true
                return 'Обязательно для заполнения'
            },
        ],
        error:{},
        field: {
            withdrawal: {
                id: null,
                wallet: null,
                amount: null,
            },
        },
        dialog: {
            withdrawal: {
                create: false,
                delete: false
            }
        },
        loading: {
            withdrawal: {
                create: false,
            },
        },
        tableHeader: [
            { title: 'Дата', value: 'created_at', key: 'created_at', class: 'header-first' },
            { title: 'Кошелек', value: 'wallet', key: 'wallet' },
            {
                title: 'Сумма в рублях',
                key: 'amount',
                value: 'amount',
            },
            { title: 'Сумма в USDT', value: 'amount_usdt', key: 'amount_usdt' },
            { title: 'Статус', value: 'status', key: 'status' },
        ]
    }),
    mounted() {
        this.getData(1)
    },
    computed: {
        ...mapGetters([
            'GET_MY_WITHDRAWALS',
            'GET_MY_BALANCE_RUB',
            'GET_MY_BALANCE_USDT',
            'GET_MY_WITHDRAWALS_SUM',
        ]),
    },
    methods: {
        ...mapActions([
            'GET_MY_WITHDRAWALS_API',
            'GET_MY_BALANCE_FROM_API',
            'GET_MY_WITHDRAWALS_SUM_FROM_API'
        ]),
        async getData(page) {
            await this.GET_MY_WITHDRAWALS_API(page);
            await this.GET_MY_BALANCE_FROM_API();
            await this.GET_MY_WITHDRAWALS_SUM_FROM_API();
        },
        createWithdrawal() {
            this.field.withdrawal = {
                id: null,
                wallet: null,
                amount: null,
            }
            this.dialog.withdrawal.create = true
        },
        storeWithdrawal() {
            this.loading.withdrawal.create = true
            axios.post('/api/v1/withdrawal', this.field.withdrawal).then(
                (response) => {
                    if (response.data.code === 200) {
                        this.$refs.message.show(response.data.message)
                        this.field.withdrawal = {
                            wallet: null,
                            amount: null,
                        }
                        this.dialog.withdrawal.create = false
                        this.getData(1)
                    } else {
                        this.$refs.message.show(response.data.message, 'warning')
                    }
                }
            ).catch((error) => {
                if(error.response.data.code === 422){
                    for(let i in error.response.data.errors){
                        this.error[i]=error.response.data.errors[i][0]
                    }
                }
            })
                .finally((response) => {
                    this.loading.withdrawal.create = false
                })
        },
    },
}
</script>

<style scoped>

</style>
