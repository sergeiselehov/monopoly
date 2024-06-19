<template>
    <SubHeader title="Массовые выплаты"/>
    <v-sheet
        class="pa-8"
        color="transparent"
    >
        <v-row>
            <v-col>
                <v-row>
                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="карта получателя"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.destination_card_number"
                        ></v-text-field>
                    </v-col>

                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-select
                            v-if="statuses && statuses.length > 0"
                            multiple
                            variant="outlined"
                            bg-color="input-bg"
                            label="Статусы"
                            :items="statuses"
                            item-title="name_ru" item-value="id"
                            v-model="filter.selectedStatuses"
                        />
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            label="Идентификатор платежа"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.uuid"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col
                        lg="4"
                        md="4"
                        sm="5"
                    >
                        <v-text-field
                            v-model="filter.date_from"
                            label="Дата начала"
                            variant="outlined"
                            v-maska:[options]
                            bg-color="input-bg"
                            placeholder="ДД.ММ.ГГГГ"
                            append-inner-icon="mdi-calendar"
                            id="menu-activator"
                        ></v-text-field>
                    </v-col>

                    <v-col
                        lg="4"
                        md="4"
                        sm="5"
                    >
                        <v-text-field
                            v-model="filter.date_to"
                            label="Дата окончания"
                            variant="outlined"
                            v-maska:[options]
                            bg-color="input-bg"
                            placeholder="ДД.ММ.ГГГГ"
                            append-inner-icon="mdi-calendar"
                            id="menu2-activator"
                        ></v-text-field>
                    </v-col>
                    <v-col
                        lg="3"
                        md="3"
                        sm="5"
                        class="d-flex flex justify-space-around"
                    >
                        <v-btn
                            class="mt-2"
                            prepend-icon="mdi mdi-magnify"
                            color="white"
                            variant="flat"
                            @click="getData(1)"
                        >
                            Поиск
                        </v-btn>
                        <v-btn
                            class="mt-2"
                            prepend-icon="mdi mdi-export"
                            color="white"
                            variant="flat"
                            @click="excelExport"
                        >
                            Экспорт
                        </v-btn>
                    </v-col>
                    <v-spacer></v-spacer>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
    <Pagination :data="this.pagination" @pagination-change-page="getData"/>
    <v-table v-if="this.GET_WITHDRAWAL_PAYMENTS!== undefined &&
                   this.GET_WITHDRAWAL_PAYMENTS !== null &&
                   this.GET_WITHDRAWAL_PAYMENTS.length > 0"
             class="v-table--hover"
    >
        <thead>
        <tr>
            <th class="text-left">
                Дата транзакции
            </th>
            <th class="text-left">
                Идентификатор транзакции
            </th>
            <th class="text-left">
                External ID
            </th>
            <th class="text-left">
                Карта получателя
            </th>
            <th class="text-left">
                Сумма
            </th>
            <th class="text-left">
                Статус
            </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_WITHDRAWAL_PAYMENTS"
            :key="item.uuid"
        >
            <td>{{ item.created_at }}</td>
            <td>{{ item.uuid }}</td>
            <td>{{ item.external_id }}</td>
            <td>{{ item.destination_card_number }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.status }}</td>
        </tr>
        </tbody>
    </v-table>
    <Pagination :data="this.pagination" @pagination-change-page="getData"/>

</template>

<script>
import {mapActions, mapGetters} from "vuex";
import SubHeader from "./shared/SubHeader.vue";
import Pagination from "./helpers/Pagination.vue";
import { vMaska } from "maska"
import axios from "axios";
import { saveAs } from 'file-saver';

export default {
    components: {
        Pagination,
        SubHeader,
    },
    name: "Transactions",
    directives: {maska: vMaska},
    data: () => ({
        options: {mask: '##.##.####'},
        pagination: null,
        checkboxes: [],
        statuses: [],
        filter: {
            selectedStatuses: [],
            type: null,
            date_from: null,
            date_to: null,
            destination_card_number: null,
            uuid: null,
        },
        list_items: [
            'p2p', 'c2c', 'E-COM'
        ],
        landscape: false,
        reactive: false,
    }),
    mounted() {
        this.getData(1)
    },
    computed: {
        ...mapGetters([
            'GET_WITHDRAWAL_PAYMENTS',
            'GET_PAGINATION'
        ]),
    },
    methods: {
        ...mapActions([
            'GET_WITHDRAWAL_PAYMENTS_FROM_API'
        ]),
        async getData(page) {
            await this.GET_WITHDRAWAL_PAYMENTS_FROM_API({
                'page': page,
                'data': this.filter
            }).then(response => {
                this.pagination = response.pagination;
                this.statuses = response.payload.statuses;
            });
        },
        excelExport() {
            axios.post('/api/v1/withdrawal-payments/export', this.filter)
                .then(response => {
                    let file = atob(response.data.data['file']);
                    let byteNumbers = new Array(file.length);
                    for (let i = 0; i < file.length; i++) {
                        byteNumbers[i] = file.charCodeAt(i);
                    }
                    let byteArray = new Uint8Array(byteNumbers);
                    let blob = new Blob([byteArray], {type: response.data.data['type']});

                    saveAs(blob, response.data.data['file_name'], {autoBom: true});
                })
                .catch(error => {
                    console.log(error)
                })
                .finally(() => {

                });
        },
    }
}
</script>

<style scoped>
.custom-radio {
    color: white;
}

.column-first {
    border-right: 1px solid rgba(243, 243, 243, 0.3);
}
</style>
