<template>
    <!-- Users Data -->
    <v-card class="rounded-xl" color="common-light-gray">
        <v-toolbar color="common-blue" density="compact" class="rounded-xl">
            <v-toolbar-title>Изменить данные</v-toolbar-title>

        </v-toolbar>
        <v-card-text class="py-2">
            <!--
            <div v-if="collection.user!==null" class="d-flex justify-center my-3">
                <img width="100" :src="field.user.avatar===null?collection.user.avatar:field.user.avatar"/>
            </div>
            <div class="d-flex justify-center my-3">
                <v-btn prepend-icon="mdi-image" color="success" @click="$refs.avatar.click()" variant="flat">
                    Change Avatar
                </v-btn>
            </div>
            -->
            <input type="file" @change="browseAvatar" accept="image/*" ref="avatar" v-show="false"/>
            <v-text-field variant="outlined" density="compact" label="Имя" v-model="field.user.name"/>
            <v-text-field variant="outlined" density="compact" label="Почта" v-model="field.user.email"/>
            <v-alert variant="text" color="red" v-if="field.user.id!==null" class="text-center">
                Если не хотитет менять пароль, оставьте поля пустыми
            </v-alert>
            <v-text-field variant="outlined" density="compact" label="Пароль" v-model="field.user.password"
                          type="password"/>
            <v-text-field variant="outlined" density="compact" label="Подтверждение пароля"
                          v-model="field.user.password_confirmation" type="password"/>
        </v-card-text>
        <v-card-actions>
            <v-spacer/>
            <v-btn :loading="loading.user.update" :disabled="loading.user.update" variant="flat" color="success"
                   @click="updateUser">Изменить
            </v-btn>
        </v-card-actions>
        <toast ref="message"/>
    </v-card>
</template>

<script>
import Container from "./helpers/Container.vue";
import Toast from "./helpers/Toast.vue";
import {mapGetters} from "vuex";


export default {
    name: "Account",
    components: {Toast, Container},
    data() {
        return {
            collection: {
                user: JSON.parse(localStorage.getItem('user'))
            },
            field: {
                user: {
                    id: null,
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    avatar: null
                }
            },
            dialog: {},
            loading: {
                user: {
                    update: false
                }
            },
            selected: {},
            option: {},
            table: {},
        }
    },
    methods: {
        browseAvatar(e) {
            const file = e.target.files[0]
            if (!file.type.includes("image/")) {
                this.$refs.toast.show("File type is not supported", 'red')
                return
            }
            if (typeof FileReader === "function") {
                const reader = new FileReader()
                reader.onload = event => {
                    this.field.user.avatar = event.target.result
                }
                reader.readAsDataURL(file)
            } else {
                this.$refs.toast.show("Sorry, FileReader API not supported", 'red')
            }
        },
        updateUser() {
            this.loading.user.update = true
            axios.post('/api/v1/account/' + this.field.user.id, this.field.user).then(res => {
                if (res.data.code === 200) {
                    localStorage.setItem('user', JSON.stringify(res.data.data))
                    this.$store.commit('setUser', res.data.data)
                    this.$refs.message.show(res.data.message)
                } else {
                    this.$refs.message.show(res.data.message, 'warning')
                }
            }).finally(() => {
                this.loading.user.update = false
            })
        }
    },
    computed: {
        ...mapGetters(['user']),
    },
    mounted() {
        let user = this.$store.state.user
        if(user!==null){
            this.field.user.id = user.id
            this.field.user.name = user.name
            this.field.user.email = user.email
        }
    },
    watch: {
        user: function (user) {
            this.field.user.id = user.id
            this.field.user.name = user.name
            this.field.user.email = user.email
        }
    }
}
</script>

<style scoped>

</style>
