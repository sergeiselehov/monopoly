<template>
    <SubHeader title="Транзакции"/>
    <v-sheet
        class="pa-8"
        color="transparent"
    >
        <v-row>
            <!--
            <v-col cols="2" class="column-first">
                    <v-checkbox
                        v-model="checkboxes"
                        label="P2P"
                        density="compact"
                    ></v-checkbox>
                    <v-checkbox
                        v-model="checkboxes"
                        label="C2C"
                        density="compact"
                    ></v-checkbox>
                    <v-checkbox
                        v-model="checkboxes"
                        label="E-COM"
                        density="compact"
                    ></v-checkbox>
                    <v-checkbox
                        v-model="checkboxes"
                        label="Общее"
                        density="compact"
                    ></v-checkbox>
            </v-col>
            -->
            <v-col>
                <v-row>
                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="карта получателя"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.card_from"
                        ></v-text-field>
                    </v-col>

                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="карта отправителя"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.card_to"
                        ></v-text-field>
                    </v-col>

                    <v-col lg="4" md="4" sm="6" cols="12">
                        <v-text-field
                            label="Идентификатор платежа"
                            variant="outlined"
                            bg-color="input-bg"
                            v-model="filter.uuid"
                        ></v-text-field>
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
                        lg="5"
                        md="5"
                        sm="6"
                    >
                        <v-text-field
                            v-model="filter.date_from"
                            label="Дата начала"
                            variant="outlined"
                            bg-color="input-bg"
                            append-inner-icon="mdi-calendar"
                            readonly
                            id="menu-activator"
                        ></v-text-field>
                        <v-menu
                            ref="start_date"
                            v-model="menu"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="slide-x-transition"
                            offset-y
                            min-width="auto"
                            activator="#menu-activator"
                        >
                            <v-locale-provider locale="ru">
                                <v-date-picker
                                    title="Выберите дату начала поиска"
                                    v-model="datePickerStart"
                                    :first-day-of-week="1"
                                    @click:save="hideMenu()"
                                    @click:cancel="hideMenu()"
                                    @update:modelValue="setModelValue('start')"
                                ></v-date-picker>
                            </v-locale-provider>
                        </v-menu>
                    </v-col>


                    <v-col
                        lg="5"
                        md="5"
                        sm="6"
                    >
                        <v-text-field
                            v-model="filter.date_to"
                            label="Дата окончания"
                            variant="outlined"
                            bg-color="input-bg"
                            append-inner-icon="mdi-calendar"
                            readonly
                            id="menu2-activator"
                        ></v-text-field>
                        <v-menu
                            ref="start_date"
                            v-model="menu2"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="slide-x-transition"
                            offset-y
                            min-width="auto"
                            activator="#menu2-activator"
                        >
                            <v-locale-provider locale="ru">
                                <v-date-picker
                                    title="Выберите дату окончания поиска"
                                    v-model="datePickerEnd"
                                    :first-day-of-week="1"
                                    @click:save="hideMenu()"
                                    @click:cancel="hideMenu()"
                                    @update:modelValue="setModelValue('end')"
                                ></v-date-picker>
                            </v-locale-provider>
                        </v-menu>
                    </v-col>
                    <v-col
                        lg="2"
                        md="2"
                        sm="6"
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
                    </v-col>
                    <v-spacer></v-spacer>
                </v-row>
            </v-col>
        </v-row>
    </v-sheet>
    <v-table v-if="this.GET_MY_TRANSACTIONS!== undefined &&
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
                карта получателя
            </th>
            <th class="text-left">
                Сумма
            </th>
            <th class="text-left">
                статус
            </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_MY_TRANSACTIONS"
            :key="item.uuid"
        >
            <td>{{ item.created_at }}</td>
            <td>{{ item.uuid }}</td>
            <td>{{ item.destination_card }}</td>
            <td>{{ item.amount }}</td>
            <td>{{ item.status }}</td>
        </tr>
        </tbody>
    </v-table>

</template>

<script>
import {mapActions, mapGetters} from "vuex";
import { VDatePicker} from "vuetify/components";
import axios from "axios";
import DateFnsAdapter from '@date-io/date-fns'
import ru from 'date-fns/locale/ru'
import SubHeader from "./shared/SubHeader.vue";


export default {
    components:{
        SubHeader,
        VDatePicker
    },
    name: "Transactions",
    data: () => ({
        checkboxes: [],
        filter:{
            type:null,
            date_from:null,
            date_to:null,
            card_from:null,
            card_to:null,
            uuid:null,
        },
        adapter: DateFnsAdapter,
        locale: {
            ru: ru,
        },
        start_date:'',
        end_date:'',
        datePickerStart: '',
        datePickerEnd: '',
        menu: false,
        menu2: false,
        list_items:[
            'p2p', 'c2c', 'E-COM'
        ],
        landscape: false,
        reactive: false,
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
            this.$store.dispatch('GET_TRANSACTIONS_FROM_API', {page:page,data:this.filter} )
            //await this.GET_TRANSACTIONS_FROM_API(page, 'test');
        },
        hideMenu(){
            this.menu=false
            this.menu2=false
        },
        setModelValue(type){
            switch(type){
                case 'start':
                    this.filter.date_from = this.formatDate(this.datePickerStart)
                    break;
                case 'end':
                    this.filter.date_to = this.formatDate(this.datePickerEnd)
                    break;
            }

        },
        formatDate(date) {
            if (!date) {
                return null
            }
            let dateObject = Date.parse(date);
            let tmp = new Date(dateObject);

            return   ('0' + tmp.getDate()).slice(-2) + '.' +
                ('0' + (tmp.getMonth() + 1)).slice(-2) + '.' +
                ('000' + tmp.getFullYear()).slice(-4)
        },

    },
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
