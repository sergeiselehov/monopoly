<template>
    <v-container style="height: 100dvh;">
        <v-row align="center" class="h-100"
        >
<!--            <v-col-->
<!--                class="h-100 d-lg-flex d-none">-->
<!--                <v-sheet class="bg-login d-flex align-center pa-4 pa-lg-12 rounded-lg w-100 h-100" color="primary">-->
<!--                    <v-img-->
<!--                        style="height: 64px; width: 273px;"-->
<!--                        src="/assets/img/logo.png"-->
<!--                    />-->
<!--                </v-sheet>-->
<!--            </v-col>-->
            <v-col
                class="v-col-md-9 v-col-sm-12 v-col-lg-9"
            >
                <v-sheet
                    class="mx-auto v-col-3"
                    max-width="450"
                    color="transparent"
                >
                    <v-img
                        style="height: 64px; width: 273px;"
                        src="/assets/img/logo.png"
                    />
                    <v-sheet
                        class="mt-2 mb-10"
                        color="transparent"
                    >
                        <h1>Вход</h1>
                        <p>введите адрес электронной почты и пароль</p>
                    </v-sheet>
                    <v-form>
                        <v-text-field
                            class="mb-2 mt-2"
                            clearable

                            hint="email"
                            variant="outlined"
                            prepend-inner-icon="mdi-email"
                            density="comfortable"
                            v-model="field.user.email"
                        ></v-text-field>

                        <v-text-field
                            clearable
                            variant="outlined"
                            prepend-inner-icon="mdi-lock"
                            density="comfortable"
                            hint="пароль"
                            :type="field.user.showPassword?'text':'password'"
                            :append-inner-icon="field.user.showPassword?'mdi-eye-off':'mdi-eye'"
                            @click:appendInner="field.user.showPassword=!field.user.showPassword"
                            v-model="field.user.password"
                        ></v-text-field>

                        <v-btn class="my-2 text-none mb-7"
                               block
                               color="primary"
                               size="x-large"
                               variant="elevated"
                               density="comfortable"
                               @click="login"
                               :disabled="loading.submit"
                               :loading="loading.submit"
                        >Войти
                        </v-btn>
                        <!--<v-btn class="my-2"
                               block
                               color="info"
                               size="large"
                               variant="text"
                               density="comfortable"
                               href="/register"
                        >Register
                        </v-btn>-->
                    </v-form>
                    <v-divider/>
                    <v-btn variant="text" class="float-right">
                        Забыли пароль?
                    </v-btn>
                </v-sheet>
            </v-col>
        </v-row>
        <lg-toast ref="toast"/>
    </v-container>
</template>

<script>

import LgToast from "./helpers/Toast.vue";

export default {
    name: "Login",
    components: {LgToast},
    data() {
        return {
            collection: {},
            field: {
                user: {
                    email: null,
                    password: null,
                    showPassword: false
                }
            },
            loading: {
                submit: false
            },
        }
    },
    methods: {
        login() {
            this.loading.submit = true
            axios.post('/api/v1/login', this.field.user).then(res => {
                if (res.data.code === 200) {
                    this.$refs.toast.show(res.data.message)
                    localStorage.setItem('user', JSON.stringify(res.data.data))
                    location.href = '/home'
                } else {
                    this.$refs.toast.show(res.data.message, 'red')
                }
            }).finally(() => {
                this.loading.submit = false
            })
        }
    },
}
</script>

<style scoped>
.bg-login {
    background: url("/assets/img/bg-stamp.png") center center;
}
</style>
