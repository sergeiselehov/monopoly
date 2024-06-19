<template>

</template>

<script>
export default {
    name: "Modal",
    data() {
        return {
            openModal: false
        }
    },
    props: {
        open: {
            type: Boolean,
            default: false
        },
    },
}
</script>

<style scoped>

</style>
