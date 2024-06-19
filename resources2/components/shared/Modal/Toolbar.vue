<template>
    <v-toolbar density="compact" color="ldgreen">
        <v-toolbar-title v-text="title"/>
        <v-toolbar-items>
            <v-btn @click="this.$emit('close');" variant="text">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
export default {
    name: "Toolbar",
    props: {
        title: {
            type: String,
            default: ''
        },
    },
}
</script>

<style scoped>

</style>
