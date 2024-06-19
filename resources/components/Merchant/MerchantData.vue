<template>
    <v-card width="400" v-if=" CURRENT_MERCHANT !== undefined && CURRENT_MERCHANT !== null">
        <template v-slot:title>
            Название магазина: {{CURRENT_MERCHANT.name }}
        </template>

        <template v-slot:text>
            <div>Success url: {{CURRENT_MERCHANT.success_url}}</div>
            <div>Error url: {{CURRENT_MERCHANT.fail_url}}</div>
            <div>Secret key: {{CURRENT_MERCHANT.secret_key}}</div>
        </template>
        <v-card-actions>
            <v-btn size="x-small" @click="createUser" v-if="can('merchant.user.create')">Добавить менеджера</v-btn>
        </v-card-actions>
    </v-card>


    <v-dialog width="500" v-model="dialog.user.create">
        <v-card class="rounded-xl">
            <v-toolbar density="compact" color="common-blue">
                <v-toolbar-title v-text="field.user.id===null?'Добавить':'Редактировать'"/>
                <v-spacer/>
                <v-toolbar-items>
                    <v-btn @click="dialog.user.create=false" variant="text">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text class="py-2">
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Имя"
                    v-model="field.user.name"
                    :error-messages="errors['name']"
                />
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Email"
                    v-model="field.user.email"
                    :error-messages="errors['email']"
                />
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Пароль"
                    v-model="field.user.password"
                    type="password"
                    :error-messages="errors['password']"
                />
                <v-text-field
                    variant="outlined"
                    density="compact"
                    label="Подтверждение пароля"
                    v-model="field.user.password_confirmation"
                    type="password"
                    :error-messages="errors['password_confirmation']"
                />
                <v-select
                    v-if="collection.roles!==null"
                    multiple
                    variant="outlined"
                    density="compact"
                    label="Роли"
                    :items="collection.roles.data"
                    item-title="name" item-value="id"
                    v-model="field.user.roles"
                    :error-messages="errors['roles']"
                />
            </v-card-text>
            <v-card-actions>
                <v-btn @click="dialog.user.delete=true" color="warning" v-if="field.user.id!==null">Delete</v-btn>
                <v-spacer/>
                <v-btn :loading="loading.user.update"
                       :disabled="loading.user.update"
                       variant="flat" color="success" v-if="field.user.id!==null">Сохранить
                </v-btn>
                <v-btn
                    :loading="loading.user.create"
                    :disabled="loading.user.create"
                    @click="storeUser"
                    variant="flat"
                    color="success"
                    v-if="field.user.id===null">Добавить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <lg-toast ref="message"/>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import axios from "axios";
import LgToast from "../helpers/Toast.vue";

export default {
    name: "MerchantData.vue",
    components: {LgToast},
    data: () => ({
        collection: {
            users: null,
            roles: null,
            permissions: null
        },
        errors:{},
        uuid: null,
        dialog: {
            user: {
                create: false,
                delete: false
            },
        },
        loading: {
            user: {
                create: false,
                delete: false,
                update: false,
            },
        },
        field: {
            user: {
                id: null,
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            },
        },
    }),
    computed: {
        ...mapGetters([
            'CURRENT_MERCHANT'
        ]),
    },
    created() {
    },
    mounted() {
        this.uuid = this.$route.query.uuid
        this.getData();
        this.getRoles();
    },
    methods: {
        ...mapActions([
            'GET_MERCHANT_DATA'
        ]),
        getRoles() {
            axios.get('/api/v1/role').then(res => {
                this.collection.roles = res.data.data
            }).finally(() => {

            })
        },
         getData() {
             this.GET_MERCHANT_DATA(this.uuid);
        },
        createUser() {
            this.field.user = {
                id: null,
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
            }
            this.dialog.user.create = true
        },
        storeUser() {
            this.loading.user.create = true
            this.field.user.merchant = this.uuid
            axios.post('/api/v1/add-and-attach', this.field.user).then((response) => {
                if (response.status === 200) {
                    this.$refs.message.show(response.data.data.message)
                    this.field.user = {
                        id: null,
                        name: null,
                        email: null,
                        password: null,
                        password_confirmation: null,
                    }
                    this.dialog.user.create = false
                } else {
                    this.$refs.message.show(response.data.message, 'warning')
                }
            }).catch((error)=>{
                    if(error.response.data.code === 422){
                        for(let i in error.response.data.errors){
                            this.errors[i]=error.response.data.errors[i][0]
                        }
                    }
            }).finally(() => {
                this.loading.user.create = false
            })
        },
    }
}
</script>

<style scoped>

</style>
