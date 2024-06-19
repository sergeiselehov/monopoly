<template>
    <v-pagination
        v-if="data!=null"
        v-model="page"
        :length="data.last_page"
        :total-visible="totalVisible"
        v-show="data.last_page > 1"
    ></v-pagination>
</template>

<script>
export default {
    name: "Pagination",
    emits: ['pagination-change-page'],
    props: {
        data: {
            type: Object,
            default: () => null
        },
        method: {type: Function},
        totalVisible: {
            type: String,
            default: () => undefined
        }
    },
    data: () => ({
        page: 1
    }),

    watch: {
      page(newValue){
          this.$emit('pagination-change-page', newValue);
      }
    },

}
</script>

<style scoped>

</style>
