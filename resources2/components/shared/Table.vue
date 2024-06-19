<template>
    <v-row class="pa-6">
        <v-container
            v-if="!disableHeader"
            class="table-head">
            <h2>{{title}}</h2>
            <slot name="action-btn">

            </slot>
        </v-container>
        <v-container class="table-filter">

        </v-container>
        <v-container class="table-main">
            <v-data-table
                v-if="tableData !== undefined && tableData !== null && tableData.length > 0"
                :items="tableData"
                :headers="tableHeader"
                class="table"
                height="300px"
                fixed-header
                item-key="name"
            >
                <template
                    v-if="enableAction"
                    v-slot:item.actions="{ item }"
                >
                    <v-icon
                        size="small"
                        class="me-2"
                        @click="editItem(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        size="small"
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
                <template
                    v-if="tableCustomRoles"
                    v-slot:item.roles="{ value }"
                >
                    <template v-for="(role, index) of value">
                        <TableChip :text="role.name"/>
                    </template>
                </template>
                <template
                    v-if="tableCustomPermissions"
                    v-slot:item.permissions="{ value }"
                >
                    <template v-for="(permission, index) of value">
                        <TableChip :text="permission.name"/>
                    </template>
                </template>
            </v-data-table>
        </v-container>
        <v-container class="table-footer">

        </v-container>
<!--        <v-dialog v-model="dialogDelete" max-width="500px">-->
<!--            <v-card>-->
<!--                <v-card-title class="text-h5"-->
<!--                >Are you sure you want to delete this item?</v-card-title-->
<!--                >-->
<!--                <v-card-actions>-->
<!--                    <v-spacer></v-spacer>-->
<!--                    <v-btn color="blue-darken-1" variant="text" @click="closeDelete"-->
<!--                    >Cancel</v-btn-->
<!--                    >-->
<!--                    <v-btn-->
<!--                        color="blue-darken-1"-->
<!--                        variant="text"-->
<!--                        @click="deleteItemConfirm"-->
<!--                    >OK</v-btn-->
<!--                    >-->
<!--                    <v-spacer></v-spacer>-->
<!--                </v-card-actions>-->
<!--            </v-card>-->
<!--        </v-dialog>-->
        <!-- Dialog delete role confirmation -->
<!--        <v-dialog width="350" v-model="dialog.role.delete">-->
<!--            <v-card>-->
<!--                <v-toolbar density="compact" color="warning">-->
<!--                    <v-toolbar-title>Confirmation</v-toolbar-title>-->
<!--                    <v-spacer/>-->
<!--                    <v-toolbar-items>-->
<!--                        <v-btn @click="dialog.role.delete=false" variant="text">-->
<!--                            <v-icon>mdi-close</v-icon>-->
<!--                        </v-btn>-->
<!--                    </v-toolbar-items>-->
<!--                </v-toolbar>-->
<!--                <v-card-text class="py-2">-->
<!--                    <p>Do you want to delete this selected role?</p>-->
<!--                </v-card-text>-->
<!--                <v-card-actions>-->
<!--                    <v-spacer/>-->

<!--                    <v-btn :loading="loading.role.delete" :disabled="loading.role.delete" @click="deleteRole"-->
<!--                           variant="flat" color="warning" v-if="field.role.id!==null">Удалить-->
<!--                    </v-btn>-->
<!--                </v-card-actions>-->
<!--            </v-card>-->
<!--        </v-dialog>-->
        <DialogDelete
            v-model:openDeleteDialog="dialogDelete"
            @delete-item="deleteItemConfirm"
        />
    </v-row>
</template>

<script>

import {Title} from "chart.js";
import TableChip from "./Table/TableChip.vue";
import DialogDelete from "./Table/DialogDelete.vue";

export default {
    name: "Table",
    components: {DialogDelete, TableChip},
    computed: {
        Title() {
            return Title
        }
    },
    data() {
        return {
            open: false,
            dialogDelete: false,
            item: null
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
        tableData: {
            type: Object,
            default: {}
        },
        disableHeader: {
            type: Boolean,
            default: false
        },
        tableHeader: {
            type: Object,
            default: {}
        },
        tableCustomRoles: {
            type: Boolean,
            default: false
        },
        tableCustomPermissions: {
            type: Boolean,
            default: false
        },
        enableAction: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        editItem(item) {
            this.$emit('edit-item', item);
        },
        deleteItem(item) {
            this.item = item
            this.dialogDelete = true;
        },

        deleteItemConfirm() {
            this.$emit('delete-item', this.item);
            // this.desserts.splice(this.editedIndex, 1)
            this.closeDelete()
        },

        close() {
            this.dialog = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        closeDelete() {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.desserts[this.editedIndex], this.editedItem)
            } else {
                this.desserts.push(this.editedItem)
            }
            this.close()
        },
    }
}
</script>

<style scoped lang="scss">
.table {
    background-color: transparent;
    color: white;

    ::v-deep .v-data-table-header {
        background-color: #DCDCDC;
    }

    .header-first {
        border-radius: 8px 0 0 8px;
    }


    &-header {
        font-size: 16px;
        font-weight: 500;
        line-height: 20px;

        tr {
            width: 100%;
            th:first-child {
                border-radius: 8px 0 0 8px;
            }

            th:last-child {
                border-radius: 0 8px 8px 0;
            }
        }
    }

    &-content {
        display: block;
        font-size: 16px;
        font-weight: 300;
    }
}

.table-head {
    display: flex;
    justify-content: space-between;

    h2 {
        font-size: 32px;
        font-style: normal;
        font-weight: 600;
        line-height: 20px;
        letter-spacing: -0.32px;
    }
}
</style>
