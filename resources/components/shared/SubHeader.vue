<template>
    <v-row class="mr-0.5">
        <v-col>
            <v-container class="pa-0" fluid >
                <v-sheet class="content flex-column flex-sm-row pa-8" color="ldgreen">
                    <div class="left">
                        <h2>{{ title }}</h2>
                        <p>{{ description }}</p>
                    </div>
                    <slot name="right" />
                </v-sheet>
            </v-container>
        </v-col>
    </v-row>
</template>

<script>

export default {
    name: "SubHeader",
    data() {
        return {
            open: false,
        }
    },
    props: {
        title: {
            type: String,
            default: ''
        },
        description: {
            type: String,
            default: ''
        },
    }
}
</script>

<style scoped>
.content {
    font-family: "Roboto Flex Variable", sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.left {
    h2 {
        font-size: 32px;
        font-style: normal;
        font-weight: 700;
        line-height: 20px;
        letter-spacing: -0.32px;
        margin-bottom: 16px;
    }
    P {
        font-size: 18px;
        font-style: normal;
        font-weight: 300;
        line-height: 20px;
        letter-spacing: -0.18px;
    }
}
</style>
