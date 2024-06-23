<template>
    <v-container
        fluid
        class="bg-primary"
        style="background-color: #1A1D1F; height: 100dvh; overflow: visible; padding: 0"
    >
        <v-layout>
            <v-navigation-drawer
                v-if="user!==null"
                v-model="drawer"
                :rail="rail"
                rail-width="80"
                permanent
                color="dgreen"
                class="bg-noise"
            >
                <v-container class="d-flex align-center">
                    <v-img
                        v-if="rail"
                        style="height: 24px; width: 24px; margin: 0 auto;"
                        src="/assets/img/logo-mini.png"
                    />
                    <v-img
                        v-else
                        style="height: 40px; width: 170px; margin: 0 auto; padding: 22px;"
                        src="/assets/img/logo.png"
                    />
                    <v-checkbox
                        false-icon="mdi-arrow-collapse-left"
                        true-icon="mdi-arrow-collapse-right"
                        class="switch"
                        v-model="rail"
                        hide-details
                    />
                </v-container>
                <v-list density="compact" nav :lines="false" class="items">
                    <v-list-item
                        v-for="item in menu_items"
                        :value="item.value"
                        color="common-green"
                        :to="item.to"
                    >
                        <!-- v-if="can('transaction.view')"-->
                        <template v-slot:prepend>
                            <v-icon :icon="item.icon"></v-icon>
                        </template>
                        <template v-slot:title>
                            <span class="menu-text-item">{{ item.text }}</span>
                        </template>
                    </v-list-item>
                    <v-list-item
                        value="logout"
                        color="common-green"
                        @click="logout"
                    >
                        <!-- v-if="can('transaction.view')"-->
                        <template v-slot:prepend>
                            <v-icon icon="mdi-power"></v-icon>
                        </template>
                        <template v-slot:title>
                            <span class="menu-text-item ">Выйти</span>
                        </template>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar v-if="user!==null"
                       color="dgreen"
                       density="compact"
                       class="header-bar"
            >
                <template v-slot:prepend>
                    <v-btn @click="drawer=!drawer" icon>
                        <v-app-bar-nav-icon></v-app-bar-nav-icon>
                    </v-btn>
                </template>

                <v-app-bar-title>
                    <slot name="title"/>
                </v-app-bar-title>
                <v-btn icon>
                    <v-icon>mdi-help-box</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-cog</v-icon>
                </v-btn>
                <v-btn icon>
                    <v-icon>mdi-bell</v-icon>
                </v-btn>
                <Profile/>
            </v-app-bar>
            <v-main>
                <router-view/>
            </v-main>
            <v-bottom-navigation height="40px" class="footer-wrapper" bg-color="dgreen">
                <v-footer class="h-10" color="dgreen">©2024 Все права защищены.</v-footer>
            </v-bottom-navigation>
        </v-layout>
        <toast ref="message"/>
    </v-container>
</template>

<script>
import Toast from "./Toast.vue";
import axios from "axios";
import Profile from "../shared/Profile.vue";
import {mapGetters} from "vuex";
import {useDisplay} from 'vuetify'

export default {
    name: "Container",
    components: {Toast, Profile},
    data() {
        return {
            drawer: !this.mobile,
            rail: false,
            menu_items: [
                {
                    to: '/home',
                    rule: 'home.view',
                    icon: 'mdi-hexagon-outline',
                    text: 'Главная',
                    value: 'home'
                },
                {
                    to: '/transactions',
                    rule: 'transaction.view',
                    icon: 'mdi-chart-timeline-variant',
                    text: 'Транзакции',
                    value: 'transactions'
                },
                {
                    to: '/balance',
                    rule: 'balance.view',
                    icon: 'mdi-wallet',
                    text: 'Баланс',
                    value: 'balance'
                },
                {
                    to: '/withdrawls',
                    rule: 'withdrawls.view',
                    icon: 'mdi-safe',
                    text: 'Выплаты',
                    value: 'withdrawls'
                },
                {
                    to: '/users',
                    rule: 'user.view',
                    icon: 'mdi-account-multiple',
                    text: 'Пользователи',
                    value: 'users'
                },
                {
                    to: '/roles',
                    rule: 'role.view',
                    icon: 'mdi-monitor-account',
                    text: 'Роли',
                    value: 'role'
                },
                {
                    to: '/merchants',
                    rule: 'balance.view',
                    icon: 'mdi-store-24-hour',
                    text: 'Мерчанты',
                    value: 'merchants'
                },
                {
                    to: '/support',
                    rule: 'balance.view',
                    icon: 'mdi-headphones',
                    text: 'Поддержка', value: 'support'
                },
            ]
        }
    },
    methods: {
        logout() {
            axios.post('/api/v1/logout').then(res => {
                if (res.data.code === 200) {
                    this.$refs.message.show(res.data.message)
                    setTimeout(() => {
                        localStorage.removeItem('user')
                        location.href = '/login'
                    }, 1000)
                }
            })
        },
        auth() {
            axios.post('/api/v1/user/auth').then(res => {
                if (res.data.code === 200) {
                    this.$store.commit('setUser', res.data.data)
                }
            }).catch((error) => {
                if (this.$route.name !== 'login' && error.response?.status === 401) {
                    location.href = '/login'
                }
            })
        },
    },
    mounted() {
        this.auth()
    },
    computed: {
        mobile() {
            return useDisplay().mobile
        },
        ...mapGetters(['user']),
    }
}
</script>

<script setup>

</script>

<style scoped>
.menu-text-item {
    font-size: 18px;
}

.bg-noise {
    border: 1px solid #000;
    background: url("/assets/img/bg-noice.png");
    box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}

a:last-child {
    background-color: red;
}

.items {
    height: calc(100% - 88px);
    display: flex;
    flex-direction: column;

    a:nth-child(8) {
        margin-top: auto;
    }
}

.header-bar {
    overflow: visible;
}

.footer-wrapper {
    height: 40px;

    footer {
        color: rgba(255, 255, 255, 0.30) !important;
        font-family: "Roboto Flex Variable", sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 300;
        line-height: 20px;
        letter-spacing: -0.14px;
    }
}

.bg-primary {
    background: url("/assets/img/bg-stamp.png") center center
}

</style>
