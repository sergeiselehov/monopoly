<template>
    <v-chip
        class="mr-1 mb-1"
        variant="flat"
        color="card-dark"
        v-text="text"
    />
</template>

<script>
export default {
    name: "TableChip",
    props: {
        text: {
            type: String,
            default: ''
        }
    }
}
</script>

<style scoped>

</style>
