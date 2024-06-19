<template>
    <v-card-actions class="modal-footer">
        <slot />
    </v-card-actions>
</template>

<script>
export default {
    name: "ModalFooter"
}
</script>

<style scoped>
.modal-footer {
    background-color: rgb(var(--v-theme-ldgreen));
    padding: 24px 30px;
}
</style>
