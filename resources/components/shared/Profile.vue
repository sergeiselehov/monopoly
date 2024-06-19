<template>
  <v-sheet class="profile">
      <v-avatar color="info">
          <v-icon icon="mdi-account-circle"></v-icon>
      </v-avatar>
      <v-container class="text">
        <h4 class="name">Admin</h4>
        <p class="email">User@mail.com</p>
      </v-container>
      <v-btn @click="open=!open" icon>
          <v-icon v-if="open" icon="mdi-chevron-up"></v-icon>
          <v-icon v-else icon="mdi-chevron-down"></v-icon>
      </v-btn>
      <v-sheet
          color="dgreen"
          class="mx-auto menu"
          max-width="300"
          v-if="open"
      >
          <v-list density="compact" nav :lines="false" class="list">
              <v-list-item
                  v-for="(item, i) in menu_items"
                  :key="i"
                  :value="item"
                  :to="item.to"
                  class="overflow-hidden"
              >
                  <template v-slot:prepend>
                      <v-icon class="mr-2" :icon="item.icon"></v-icon>
                      <v-list-item-title v-text="item.text"></v-list-item-title>
                  </template>
              </v-list-item>
          </v-list>
      </v-sheet>
  </v-sheet>
</template>

<script>

export default {
    name: "Profile",
    data() {
        return {
            open: false,
            menu_items:[
                {to:'/account',rule:'account.view',icon:'mdi-account',text:'редактировать профиль',value:'account'},
                {to:'/home',rule:'home.view',icon:'mdi-hexagon-outline',text:'мои обращения',value:'reports'},
                {to:'/transactions',rule:'transaction.view',icon:'mdi-chart-timeline-variant',text:'полноэкранный режим',value:'fullscreen'},
                {to:'/balance',rule:'balance.view',icon:'mdi-wallet',text:'выйти из системы',value:'exit'},
            ]
        }
    },
}
</script>

<style scoped>
.menu {
    background-color: #034027;
    position: fixed;
    top: 50px;
    right: 13px;
    width: 240px;

    .list {
        background-color: transparent;
        color: white;
        font-family: "Roboto Flex Variable", sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 300;
        line-height: 20px;
        letter-spacing: -0.14px;
    }
}

.profile {
    display: flex;
    align-items: center;
    background-color: transparent;
    width: 220px;

    .text {
        overflow: hidden;
        padding: 0 12px;

        .name {
            font-family: "Roboto Flex Variable", sans-serif;
            font-size: 18px;
            font-style: normal;
            font-weight: 500;
            line-height: 20px;
            letter-spacing: -0.18px;
        }

        .email {
            color: rgba(255, 255, 255, 0.56);
            font-family: "Roboto Flex Variable", sans-serif;
            font-size: 12px;
            font-style: normal;
            font-weight: 200;
            line-height: 20px;
            letter-spacing: -0.12px;
        }
    }
}
</style>
