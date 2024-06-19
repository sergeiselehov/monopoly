<template>
    <SubHeader title="Роли" description="Текст подсказки для раздела">
        <template #right>
            <v-btn
                v-if="can('Add Role')"
                height="48"
                variant="flat"
                text="добавить роль"
                color="action-btn"
                @click="createRole">
                <template #prepend>
                    <v-icon icon="mdi-plus"/>
                </template>
            </v-btn>
        </template>
    </SubHeader>
    <Table
        v-if="collection.roles!==null"
        title="Роли"
        :disableHeader="true"
        :table-header="tableHeaderGroups"
        :tableData="this.collection.roles.data"
        :enable-action="true"
        :table-custom-permissions="true"
        @edit-item="onEditRole"
        @delete-item="onDeleteRole"
    >
    </Table>
    <v-container fluid>
        <!-- Dialog Create/Edit Role -->
        <v-dialog width="300" v-model="dialog.role.create">
            <v-card>
                <v-toolbar density="compact" color="common-dark-blue">
                    <v-toolbar-title v-text="field.role.id===null?'Добавить':'Редактировать'"/>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn @click="dialog.role.create=false" variant="text">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text class="py-2">
                    <v-text-field variant="outlined" density="compact" label="Name" v-model="field.role.name"/>
                    <v-card-title>Permission</v-card-title>
                    <template v-if="collection.permissions!==null" v-for="(item, index) of collection.permissions">
                        <v-checkbox density="compact" hide-details :value="item.id" :label="item.name"
                                    v-model="field.role.permissions" multiple/>
                    </template>
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="dialog.role.delete=true" color="warning" v-if="field.role.id!==null">Delete Role
                    </v-btn>
                    <v-spacer/>
                    <v-btn :loading="loading.role.update" :disabled="loading.role.update" @click="updateRole"
                           variant="flat" color="success" v-if="field.role.id!==null">Обновить
                    </v-btn>
                    <v-btn :loading="loading.role.create" :disabled="loading.role.create" @click="storeRole"
                           variant="flat" color="success" v-if="field.role.id===null">Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
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
                roles: null,
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
                },
                role: {
                    id: null,
                    name: null,
                    permissions: []
                }
            },
            dialog: {
                user: {
                    create: false,
                    delete: false
                },
                role: {
                    create: false,
                    delete: false
                }
            },
            loading: {
                user: {
                    create: false,
                    delete: false,
                    update: false,
                },
                role: {
                    create: false,
                    delete: false,
                    update: false,
                }
            },
            tableHeaderGroups: [
                { title: 'Название', value: 'name', key: 'name', class: 'header-first' },
                { title: 'Разрешения', value: 'permissions', key: 'permissions' },
                { title: 'Дествия', key: 'actions', value: 'actions' },
            ],
        }
    },
    methods: {
        selectRole(role) {
            const permissions = role.permissions.map(p => p.id);
            this.field.role.id = role.id
            this.field.role.name = role.name
            this.field.role.permissions = permissions
        },
        onEditRole(role) {
            this.selectRole(role)
            this.dialog.role.create = true
        },
        onDeleteRole(role) {
            this.selectRole(role)
            this.deleteRole()
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

        getRoles() {
            axios.get('/api/v1/role').then(res => {
                this.collection.roles = res.data.data
                console.log('this.collection.roles', this.collection.roles)
            }).finally(() => {

            })
        },
        createRole(showDialog = true) {
            this.field.role = {
                id: null,
                name: null,
                permissions: []
            }
            this.dialog.role.create = showDialog
        },
        storeRole() {
            this.loading.role.create = true
            axios.post('/api/v1/role', this.field.role).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.field.role = {
                        id: null,
                        name: null,
                        permissions: []
                    }
                    this.dialog.role.create = false
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.create = false
            })
        },
        updateRole() {
            this.loading.role.update = true
            axios.patch('/api/v1/role/' + this.field.role.id, this.field.role).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.auth()
                    this.createRole(false)
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.update = false
            })
        },
        deleteRole() {
            this.loading.role.delete = true
            axios.delete('/api/v1/role/' + this.field.role.id).then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    this.dialog.role.delete = false
                    this.dialog.role.create = false
                    this.getRoles()
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.role.delete = false
            })
        },
        getPermissions() {
            axios.get('/api/v1/permission').then(res => {
                this.collection.permissions = res.data.data
            }).finally(() => {

            })
        },
    },
    mounted() {
        // this.getUsers()
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
