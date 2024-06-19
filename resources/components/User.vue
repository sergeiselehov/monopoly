<template>
    <SubHeader title="Пользователи" description="Текст подсказки для раздела">
        <template #right>
            <v-btn
                v-if="can('user.create')"
                height="48"
                variant="flat"
                text="добавить пользователя"
                color="action-btn"
                @click="createUser">
                <template #prepend>
                    <v-icon icon="mdi-plus"/>
                </template>
            </v-btn>
        </template>
    </SubHeader>
    <Table
        v-if="collection.users!==null"
        title="Пользователи"
        :disableHeader="true"
        :table-header="tableHeaderUsers"
        :tableData="this.collection.users"
        :enable-action="true"
        :table-custom-roles="true"
        @edit-item="onEditUser"
        @delete-item="onDeleteUser"
    >
    </Table>
        <!-- Dialog Create/Edit User -->
        <v-dialog width="900" v-model="dialog.user.create">
            <v-card>
                <Toolbar
                    :title="field.user.id===null?'Добавить':'Редактировать'"
                    @close="dialog.user.create=false"
                />
                <v-card-text class="py-2">
                    <v-text-field
                        variant="outlined"
                        density="compact"
                        label="Email"
                        v-model="field.user.email"
                        :error-messages="errors['email']"
                    />
                    <v-row>
                        <v-col>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Пароль"
                                v-model="field.user.password"
                                type="password"
                                :error-messages="errors['password']"
                            />
                        </v-col>
                        <v-col>
                            <v-text-field
                                variant="outlined"
                                density="compact"
                                label="Подтвердить пароль"
                                v-model="field.user.password_confirmation"
                                type="password"
                                :error-messages="errors['password_confirmation']"
                            />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <v-select
                                v-if="collection.roles!==null"
                                multiple variant="outlined"
                                density="compact" label="Роли"
                                :items="collection.roles.data"
                                item-title="name" item-value="id"
                                v-model="field.user.roles"
                                :error-messages="errors['roles']"
                            />
                        </v-col>
                        <v-col>
                            <FormInfo description="При создании пользователя пароль автоматически отправляется на email указанный при создании" />
                        </v-col>
                    </v-row>
                    <v-alert variant="text" color="red" v-if="field.user.id!==null" class="text-center">leave blank if
                        you don't want to change password
                    </v-alert>
                </v-card-text>
                <ModalFooter>
                    <v-btn @click="dialog.user.delete=true" color="warning" v-if="field.user.id!==null">Delete</v-btn>
                    <v-spacer/>
                    <v-btn :loading="loading.user.update" :disabled="loading.user.update" @click="updateUser"
                           variant="flat" color="success" v-if="field.user.id!==null">Обновить
                    </v-btn>
                    <v-btn :loading="loading.user.create" :disabled="loading.user.create" @click="storeUser"
                           variant="flat" color="action-btn" v-if="field.user.id===null">Сохранить
                    </v-btn>
                </ModalFooter>
            </v-card>
        </v-dialog>
    <lg-toast ref="message"/>
</template>

<script>
import axios from "axios";
import LgToast from "./helpers/Toast.vue";
import Container from "./helpers/Container.vue";
import { mapGetters} from 'vuex';
import SubHeader from "./shared/SubHeader.vue";
import Table from "./shared/Table.vue";
import Toolbar from "./shared/Modal/Toolbar.vue";
import FormInfo from "./shared/FormInfo.vue";
import Modal from "./shared/Modal/Modal.vue";
import ModalFooter from "./shared/Modal/ModalFooter.vue";
export default {
    name: "User",
    components: {ModalFooter, Modal, FormInfo, Toolbar, Table, SubHeader, Container, LgToast},
    data() {
        return {
            errors:{},
            collection: {
                users: null,
                permissions: null
            },
            field: {
                user: {
                    id: null,
                    name: null,
                    surname: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    roles: []
                }
            },
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
            tableHeaderUsers: [
                { title: 'Ник', value: 'name', key: 'name', class: 'header-first' },
                { title: 'Почта', value: 'email', key: 'email' },
                { title: 'Роли', key: 'roles', value: 'roles' },
                { title: 'Логин', key: 'login', value: 'login' },
                { title: 'Действия', value: 'actions', key: 'actions' }
            ]
        }
    },
    methods: {
        selectUser(user) {
            this.field.user.id = user.id
            this.field.user.name = user.name
            this.field.user.email = user.email
            let roles = user.roles.map(r => r.id);
            this.field.user.roles = roles
        },
        onEditUser(user) {
            this.selectUser(user)
            this.dialog.user.create = true
        },
        onDeleteUser(user) {
            this.selectUser(user)
            this.deleteUser()
        },
        deleteUser() {
            this.loading.user.delete = true
            axios.delete('/api/v1/user/' + this.field.user.id).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.dialog.user.delete = false
                    this.dialog.user.create = false
                    this.getUsers()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.delete = false
            })
        },
        auth() {
            axios.post('/api/v1/user/auth').then(res => {
                if (res.data.code === 200) {
                    localStorage.setItem('user', JSON.stringify(res.data.data))
                }else{
                    this.$refs.message.show(res.data.message, 'warning')
                }
            })
        },
        getUsers() {
            axios.get('/api/v1/user').then(res => {
                this.collection.users = res.data.data
            }).finally(() => {

            })
        },
        getPermissions() {
            axios.get('/api/v1/permission').then(res => {
                this.collection.permissions = res.data.data
            }).finally(() => {

            })
        },
        createUser() {
            this.field.user = {
                id: null,
                email: null,
                password: null,
                password_confirmation: null,
            }
            this.dialog.user.create = true
        },
        storeUser() {
            this.loading.user.create = true
            this.field.user = {...this.field.user, name: this.field.user.email} //TODO delete name
            axios.post('/api/v1/user', this.field.user).then(res => {
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
            }).catch((error)=>{
                if(error.response.status===422){
                    if(error.response.data.code === 422){
                        for(let i in error.response.data.errors){
                            this.errors[i]=error.response.data.errors[i][0]
                        }
                    }
                }
            }).finally(() => {
                this.loading.user.create = false
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
        getRoles() {
            axios.get('/api/v1/role').then(res => {
                this.collection.roles = res.data.data
            }).finally(() => {

            })
        }
    },
    mounted() {
        this.getUsers()
        this.getRoles()
        this.getPermissions()
    },
    computed:{
        ...mapGetters(['user']),
    }
}
</script>

<style scoped>

</style>
