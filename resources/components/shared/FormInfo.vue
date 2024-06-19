<template>
    <v-card
        color="ldgreen"
        title="Информация"
    >
        <v-card-text>
            <p class="info-text">{{ description }}</p>
        </v-card-text>
        <v-icon
            class="icon"
            icon="mdi-information" />
    </v-card>
</template>

<script>
export default {
    name: "FormInfo",
    props: {
        description: {
            type: String,
            default: ''
        },
    }
}
</script>

<style scoped>
.icon {
    position: absolute;
    top: 20px;
    right: 20px;
}

.info-text {
    font-weight: 300;
    font-size: 13px;
    line-height: 20px;
}
</style>
