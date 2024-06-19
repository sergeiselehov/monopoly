<template>
    <lg-toast ref="message"/>
    <SubHeader title="Выплаты" description="Текст подсказки для раздела">
        <template #right>
            <v-btn
                height="48"
                variant="flat"
                text="Добавить выплату"
                color="action-btn"
                :disabled="can('withdrawal.create')"
                @click="createWithdrawal">
                <template #prepend>
                    <v-icon icon="mdi-currency-usd"/>
<!--                    <v-icon>mdi-plus</v-icon>-->
                </template>
            </v-btn>
        </template>
    </SubHeader>
    <v-sheet
        class="pa-12 rounded-xl"
        color="transparent"
        :rounded="200"
    >
        <WithdrawlsCard balance="100 000 000" withdrawls="100 000 000" />
    </v-sheet>

    <v-table
        v-if="this.GET_MY_WITHDRAWALS !== undefined && this.GET_MY_WITHDRAWALS !== null && this.GET_MY_WITHDRAWALS.length > 0">
        <thead>
        <tr>
            <th class="text-left">
                Дата
            </th>
            <th class="text-left">
                Кошелек
            </th>
            <th class="text-left">
                Сумма в рублях
            </th>
            <th class="text-left">
                Сумма в USDT
            </th>
            <th class="text-left">
                Статус
            </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_MY_WITHDRAWALS"
            :key="item.id"
        >
            <td>{{ item.created_at }}</td>
            <td>{{ item.wallet }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.amount_usdt }}</td>
            <td>{{ item.status }}</td>
        </tr>
        </tbody>
    </v-table>
</template>

<script>

import axios from "axios";
import LgToast from "./helpers/Toast.vue";
import Container from "./helpers/Container.vue";
import {mapActions, mapGetters} from 'vuex';
import SubHeader from "./shared/SubHeader.vue";
import WithdrawlsCard from "./shared/WithdrawlsCard.vue";

export default {
    name: "withdrawals",
    components: {WithdrawlsCard, SubHeader, Container, LgToast},

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

    }),
    mounted() {
        this.getData(1)
    },
    computed: {
        ...mapGetters([
            'GET_MY_WITHDRAWALS',
            'GET_MY_BALANCE',
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
