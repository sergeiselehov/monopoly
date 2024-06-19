<template>
    <v-card class="rounded-xl">
    <v-toolbar
        color="common-blue"
        density="compact"

    >
        <v-toolbar-title>Мои обращения</v-toolbar-title>
        <v-spacer/>
        <v-toolbar-items>
            <v-btn @click="createSupportRequest" variant="text" v-if="can('merchant.create')">
                <v-icon>mdi-plus</v-icon>
                Добавить обращение
            </v-btn>
        </v-toolbar-items>
        <v-dialog
            width="600"
            v-model="dialog.support_request.create"
            draggable="true"
        >
            <v-card
            class="rounded-xl"
            >
                <v-toolbar density="compact" color="common-blue">
                    <v-toolbar-title v-text="'Добавление обращения'"/>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.support_request.create=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <v-text-field
                        variant="outlined"
                        density="compact"
                        label="Тема обращения"
                        v-model="field.support_request.theme"
                        :rules="rules"
                        :error-messages="error['theme']"
                        clearable
                    />
                    <v-select
                        :items="this.GET_SUPPORT_REQUEST_CATEGORY.categories"
                        variant="outlined"
                        class="mt-2"
                        label="Категория обращения"
                        :error-messages="error['category']"
                        v-model="field.support_request.category"
                        item-title="name"
                        item-value="id"
                        clearable
                    ></v-select>
                    <v-text-field
                        variant="outlined"
                        density="compact"
                        label="Ваш email"
                        v-model="field.support_request.email"
                        :error-messages="error['email']"
                        :rules="rules"
                        class="mt-2"
                        clearable
                    />
                    <v-text-field
                        variant="outlined"
                        density="compact"
                        label="Ваш telegram"
                        v-model="field.support_request.telegram"
                        :error-messages="error['telegram']"
                        :rules="rules"
                        class="mt-2"
                        clearable
                    />
                    <v-textarea
                        variant="outlined"
                        density="compact"
                        label="Пожалуйста, опишите сложившуюся проблему максимально полно"
                        v-model="field.support_request.description"
                        :error-messages="error['description']"
                        :rules="rules"
                        class="mt-2"
                        clearable
                    />
                </v-card-text>
                <v-card-actions class="justify-center">
                    <v-btn
                        :loading="loading.support_request.create"
                        :disabled="loading.support_request.create"
                        @click="storeSupportRequest"
                        variant="flat"
                        color="success"
                    >Отправить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-toolbar>
    <lg-toast ref="message"/>
    <v-table v-if="this.GET_MY_SUPPORT_REQUESTS !== undefined &&
    this.GET_MY_SUPPORT_REQUESTS !== null &&
    this.GET_MY_SUPPORT_REQUESTS.length > 0">
        <thead>
        <tr>
            <th class="text-left">
                Тема
            </th>
            <th class="text-left">
                Описание
            </th>
            <th class="text-left">
                Дата обращения
            </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_MY_SUPPORT_REQUESTS"
            :key="item.id"
        >
            <td>{{ item.title }}</td>
            <td>{{ item.description }}</td>
            <td>{{ item.created_date }}</td>
        </tr>
        </tbody>
    </v-table>
    </v-card>
</template>

<script>

import Container from "./helpers/Container.vue";
import LgToast from "./helpers/Toast.vue";
import {mapActions, mapGetters} from "vuex";
import axios from "axios";

export default {
    name: "Merchants",
    components: {Container, LgToast},

    data: () => ({
        request_items: ['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming'],
        rules: [
            value => {
                if (value) return true
                return 'Обязательно для заполнения'
            },
        ],
        error:{},
        field: {
            support_request: {
                id: null,
                title: null,
                category: null,
                email: null,
                telegram: null,
                description: null,
            },
        },
        dialog: {
            support_request: {
                create: false,
                delete: false
            }
        },
        loading: {
            support_request: {
                create: false,
                delete: false,
                update: false,
            },
        },

    }),
    mounted() {
        this.getSupporRequestCategory();
        this.getMySupportsRequest();
    },
    computed: {
        ...mapGetters([
            'GET_MERCHANTS',
            'GET_SUPPORT_REQUEST_CATEGORY',
            'GET_MY_SUPPORT_REQUESTS'
        ]),
    },
    methods: {
        ...mapActions([
            'GET_MERCHANTS_FROM_API',
            'GET_SUPPORT_REQUEST_CATEGORY_FROM_API',
            'GET_MY_SUPPORTS_REQUESTS_FROM_API'
        ]),
        async getSupporRequestCategory() {
            await this.GET_SUPPORT_REQUEST_CATEGORY_FROM_API();
        },
        async getMySupportsRequest() {
            await this.GET_MY_SUPPORTS_REQUESTS_FROM_API();
        },
        createSupportRequest() {
            this.field.support_request = {
                id: null,
                name: null,
            }
            this.dialog.support_request.create = true
        },
        storeSupportRequest() {
            this.loading.support_request.create = true
            axios.post('/api/v1/support-requests', this.field.support_request).then(
                (response) => {
                    if (response.data.code === 200) {
                        this.$refs.message.show(response.data.message)
                        this.field.support_request = {
                            id: null,
                            name: null,
                            success_url: null,
                            error_url: null,
                            base_url: null,
                        }
                        this.dialog.support_request.create = false
                        this.getMySupportsRequest()
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
                    this.loading.support_request.create = false
                })
        },
    },
}
</script>

<style scoped></style>
