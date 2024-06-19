<template>
    <SubHeader title="Мерчанты" description="Текст подсказки для раздела">
        <template #right>
            <v-btn
                v-if="can('merchant.create')"
                height="48"
                variant="flat"
                text="добавить мерчант"
                color="action-btn"
                @click="createMerchant">
                <template #prepend>
                    <v-icon icon="mdi-plus"/>
                </template>
            </v-btn>
        </template>
    </SubHeader>
    <Table
        v-if="this.GET_MERCHANTS !== undefined && this.GET_MERCHANTS !== null && this.GET_MERCHANTS.length > 0 || true"
        title="История выплат"
        :disableHeader="true"
        :table-header="tableHeader"
        :tableData="this.GET_MERCHANTS"
    >
    </Table>
    <v-dialog max-width="900" v-model="dialog.merchant.create">
        <v-card
            class="rounded-xl"
            color="sdgreen"
        >
            <Toolbar
                :title="field.merchant.id===null?'Добавление мерчанта':'Редактирование мерчанта'"
                @close="dialog.merchant.create=false"
            />
            <v-card title="Укажите параметры">
                <v-card-text class="py-2">
                    <v-row class="mb-2">
                        <v-col>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Название"
                                v-model="field.merchant.name"
                                :rules="rules"
                                :error-messages="error['name']"
                                clearable
                            />
                            <FormInfo description="При создании мерчанта краткая инструкция что куда и где именно указывать (+ формат)" />
                        </v-col>
                        <v-divider vertical></v-divider>
                        <v-col>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Базовый url"
                                :rules="rules"
                                v-model="field.merchant.base_url"
                                :error-messages="error['base_url']"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <label class="color-label" :class="'fail'"><span>FAIL</span></label>
                                </template>
                            </v-text-field>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Успешная ссылка для api"
                                v-model="field.merchant.success_url"
                                :error-messages="error['success_url']"
                                :rules="rules"
                                class="mt-2"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <label class="color-label" :class="'base'"><span>BASE</span></label>
                                </template>
                            </v-text-field>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Ссылка ошибки для api"
                                v-model="field.merchant.error_url"
                                :error-messages="error['error_url']"
                                :rules="rules"
                                class="mt-2 no-border-left"
                                clearable
                            >
                                <template v-slot:prepend>
                                    <label class="color-label" :class="'success'"><span>SUCCESS</span></label>
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <ModalFooter>
                    <v-btn
                        :loading="loading.merchant.create"
                        :disabled="loading.merchant.create"
                        @click="dialog.merchant.create=false"
                        variant="flat"
                        color="white"
                    >Отмена
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn
                        @click="dialog.merchant.delete=true"
                        color="warning"
                        v-if="field.merchant.id!==null"
                    >
                        Delete
                    </v-btn>
                    <v-spacer/>
                    <v-btn
                        :loading="loading.merchant.update"
                        :disabled="loading.merchant.update"
                        @click="updateUser"
                        variant="flat"
                        color="action-btn"
                        v-if="field.merchant.id!==null">Обновить
                    </v-btn>
                    <v-btn
                        :loading="loading.merchant.create"
                        :disabled="loading.merchant.create"
                        @click="storeMerchant"
                        variant="flat"
                        color="action-btn"
                        v-if="field.merchant.id===null"
                    >Сохранить
                    </v-btn>
                </ModalFooter>
            </v-card>
        </v-card>
    </v-dialog>
    <lg-toast ref="message"/>
</template>

<script>

import axios from "axios";
import LgToast from "./helpers/Toast.vue";
import Container from "./helpers/Container.vue";
import {mapActions, mapGetters} from 'vuex';
import SubHeader from "./shared/SubHeader.vue";
import Table from "./shared/Table.vue";
import Modal from "./shared/Modal/Modal.vue";
import Toolbar from "./shared/Modal/Toolbar.vue";
import FormInfo from "./shared/FormInfo.vue"
import ModalFooter from "./shared/Modal/ModalFooter.vue";

export default {
    name: "Merchants",
    components: {ModalFooter, FormInfo, Toolbar, Modal, Table, SubHeader, Container, LgToast},

    data: () => ({
        color: 'green',
        rules: [
            value => {
                if (value) return true
                return 'Обязательно для заполнения'
            },
        ],
        error:{},
        field: {
            merchant: {
                id: null,
                name: null,
                success_url: null,
                error_url: null,
                base_url: null,
            },
        },
        dialog: {
            merchant: {
                create: false,
                delete: false
            }
        },
        loading: {
            merchant: {
                create: false,
                delete: false,
                update: false,
            },
        },
        tableHeader: [
            { title: 'Название', value: 'name', key: 'name', class: 'header-first' },
            { title: 'Url при успешной операции', value: 'success_url', key: 'success_url' },
            { title: 'Url при ошибке', key: 'fail_url', value: 'fail_url' },
            { title: 'Дата добавления', value: 'created_date', key: 'created_date' },
            { title: 'Действия', value: 'actions', key: 'actions' },
        ]
    }),
    mounted() {
        this.getData(1)
    },
    computed: {
        ...mapGetters([
            'GET_MERCHANTS'
        ]),
    },
    methods: {
        ...mapActions([
            'GET_MERCHANTS_FROM_API'
        ]),
        async getData(page) {
            await this.GET_MERCHANTS_FROM_API(page);
        },
        createMerchant() {
            this.field.merchant = {
                id: null,
                name: null,
            }
            this.dialog.merchant.create = true
        },
        showMerchantData(uuid){
            this.$router.push('merchant-data?uuid='+uuid)
        },
        storeMerchant() {
            this.loading.merchant.create = true
            axios.post('/api/v1/merchant', this.field.merchant).then(
                (response) => {
                    if (response.data.code === 200) {
                        this.$refs.message.show(response.data.message)
                        this.field.merchant = {
                            id: null,
                            name: null,
                            success_url: null,
                            error_url: null,
                            base_url: null,
                        }
                        this.dialog.merchant.create = false
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
                    this.loading.merchant.create = false
                })
        },
        updateUser() {
            this.loading.user.update = true
            axios.patch('/api/v1/user/' + this.field.user.id, this.field.user).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.field.user = {
                        id: null,
                        name: null,
                        email: null,
                        password: null,
                        password_confirmation: null,
                    }
                    this.dialog.user.create = false
                    this.getUsers()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.update = false
            })
        },
    },
}
</script>

<style scoped>
.success {
    color: #328048;
    background-color: #EAFBE7;
    border-color: #C6F0C2;
}

.base {
    color: #326480;
    background-color: #E7FBF9;
    border-color: #C2E8F0;
}

.fail {
    color: #D02B20;
    background-color: #FCECEA;
    border-color: #F5C0B8;
}

.color-label {
    margin-right: -16px;
    padding-left: 14px;
    padding-right: 10px;
    height: 100%;
    border-radius: 4px 0 0 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid;
}

.no-border-left {
    border-left: none;
}
</style>
