<template>
    <!-- Dialog delete user confirmation -->
    <v-dialog width="350" v-model="open">
        <v-card>
            <v-toolbar density="compact" color="ldgreen">
                <v-toolbar-title>Confirmation</v-toolbar-title>
                <v-spacer/>
                <v-toolbar-items>
                    <v-btn @click="open=false" variant="text">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-toolbar-items>
            </v-toolbar>
            <v-card-text class="py-2">
                <p>Вы хотите удалить выбранный элемент?</p>
            </v-card-text>
            <v-card-actions>
                <v-spacer/>
                <v-btn @click="deleteItem"
                       variant="flat" color="white">Удалить
                </v-btn>
<!--  TODO              <v-btn :loading="loading.user.delete" :disabled="loading.user.delete" @click="deleteUser"-->
<!--                       variant="flat" color="warning">Удалить-->
<!--                </v-btn>-->
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    name: "DialogDelete",
    data() {
        return {
            open: false
        }
    },
    props: {
        item: {
          type: Object,
          default: () => null
        },
        enable: {
            type: Boolean,
            default: false
        },
        openDeleteDialog: {
            type: Boolean,
            default: false
        }
    },
    watch: {
        open: {
            handler (newVal, _) {
                this.$emit('update:openDeleteDialog', newVal)
            }
        },
        openDeleteDialog: {
            immediate: true,
            handler (newVal, _) {
                this.open = this.openDeleteDialog;
                this.$emit('update:openDeleteDialog', newVal)
            }
        }
    },
    methods: {
        deleteItem() {
            this.$emit('delete-item')
        }
    }
}
</script>

<style scoped>

</style>
