<template>
    <SubHeader title="Транзакции"/>
    <v-sheet
        class="pa-8"
        color="transparent"
    >
        <v-row>
            <v-col>
                <v-row>
                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="Карта получателя"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.card_to"
                        ></v-text-field>
                    </v-col>

                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="Карта отправителя"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.card_from"
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
                            placeholder="ДД.ММ.ГГГГ"
                            bg-color="input-bg"
                            append-inner-icon="mdi-calendar"
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
                            placeholder="ДД.ММ.ГГГГ"
                            v-maska:[options]
                            variant="outlined"
                            bg-color="input-bg"
                            append-inner-icon="mdi-calendar"
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
    <v-table v-if="this.GET_MY_TRANSACTIONS !== undefined &&
                   this.GET_MY_TRANSACTIONS !== null &&
                   this.GET_MY_TRANSACTIONS.length > 0"
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
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_MY_TRANSACTIONS"
            :key="item.uuid"
        >
            <td>{{ item.created_at }}</td>
            <td>{{ item.uuid }}</td>
            <td>{{ item.external_id }}</td>
            <td>{{ item.destination_card }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.status }}</td>
            <td>
                <v-dialog max-width="500">
                    <template v-slot:activator="{ props: activatorProps }">
                        <v-btn
                            v-bind="activatorProps"
                            text="Открыть спор"
                            color="white"
                            variant="flat"
                            @click="files = []"
                        ></v-btn>
                    </template>

                    <template v-slot:default="{ isActive }">
                        <v-card class="align-center" title="Открытие спора">
                            <v-card class="d-flex flex-column justify-center align-center">
                                <p>по платежу</p>
                                <p><b>UUID:</b> {{ item.uuid }}</p>
                                <p><b>Дата:</b> {{ item.created_at }}</p>
                                <p><b>Карта назначения:</b> {{ item.destination_card }}</p>
                                <p><b>Карта плательщика:</b> {{ item.customer_card_number }}</p>
                                <p><b>Статус:</b> {{ item.status }}</p>

                                <v-text-field
                                    class="mt-3 w-50"
                                    label="Сумма по чеку"
                                    variant="outlined"
                                    density="compact"
                                    v-model="bill_amount"
                                />

                                <label for="file-upload" class="custom-file-upload">
                                    Прикрепить файл
                                </label>
                                <input id="file-upload" type="file" @change="handleFilesSelect"/>

                                <div class="file-names" v-if="files">
                                    <div v-for="(file, index) in files" :key="index">{{ file.name }}</div>
                                </div>
                            </v-card>
                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn
                                    text="Отменить"
                                    @click="isActive.value = false"
                                ></v-btn>
                                <v-btn
                                    text="Сохранить"
                                    @click="isActive.value = false; storeDispute(item)"
                                ></v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
            </td>
        </tr>
        </tbody>
    </v-table>
    <lg-toast ref="message"/>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import axios from "axios";
import SubHeader from "./shared/SubHeader.vue";
import Pagination from "./helpers/Pagination.vue";
import {vMaska} from "maska"
import {saveAs} from 'file-saver';
import LgToast from "./helpers/Toast.vue";

export default {
    components: {
        LgToast,
        Pagination,
        SubHeader,
    },
    directives: {maska: vMaska},
    name: "Transactions",
    data() {
        return {
            options: {mask: '##.##.####'},
            checkboxes: [],
            files: [],
            bill_amount: null,
            statuses: [],
            filter: {
                selectedStatuses: [],
                type: null,
                date_from: null,
                date_to: null,
                card_from: null,
                card_to: null,
                uuid: null,
            },
            list_items: [
                'p2p', 'c2c', 'E-COM'
            ],
            landscape: false,
            reactive: false,
            pagination: null,
        }
    },
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
            this.$store.dispatch('GET_TRANSACTIONS_FROM_API', {page: page, data: this.filter})
                .then(response => {
                    this.pagination = response.pagination;
                    this.statuses = response.payload.statuses;
                })

            //await this.GET_TRANSACTIONS_FROM_API(page, 'test');
        },
        handleFilesSelect(event) {
            this.files = Array.from(event.target.files);
        },
        storeDispute(payment) {
            const formData = new FormData();
            formData.append('amount', payment.amount);
            formData.append('bill_time', payment.created_at_for_dispute);
            formData.append('bill_amount', this.bill_amount);
            formData.append('bill_image', this.files[0] ?? null);

            axios.post('https://api.paymentsgate.pro/api/v1/dispute/' + payment.uuid, formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then((res) => {
                this.$refs.message.show(res.data.message)
                this.files = [];
            }).catch(function (error) {
                this.$refs.message.show(error.data.message, 'warning')
            });
        },
        excelExport() {
            axios.post('/api/v1/transactions/export', this.filter)
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

input[type="file"] {
    display: none;
}

.custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    max-width: 200px;
    min-height: 50px;
    padding: 1px 12px;
    cursor: pointer;
    border-radius: 8px;
    margin: 5px 0 24px 0;
    text-align: center;
    color: #AC80A0;
    line-height: 50px;
}
</style>
