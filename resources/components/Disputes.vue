<template>
    <SubHeader title="Споры"/>
    <Pagination :data="this.pagination" @pagination-change-page="getData"/>
    <v-table v-if="this.GET_DISPUTES !== undefined &&
                   this.GET_DISPUTES !== null &&
                   this.GET_DISPUTES.length > 0"
             class="v-table--hover"
    >
        <thead>
        <tr>
            <th class="text-left">
                Дата
            </th>
            <th class="text-left">
                Идентификатор транзакции
            </th>
            <th class="text-left">
                Сумма
            </th>
            <th class="text-left">
                Статус
            </th>
        </tr>
        </thead>
        <tbody>
        <tr
            v-for="item in this.GET_DISPUTES"
            :key="item.uuid"
        >
            <td>{{ item.bill_time }}</td>
            <td>{{ item.uuid }}</td>
            <td>{{ item.bill_amount }}</td>
            <td>{{ item.status }}</td>
        </tr>
        </tbody>
    </v-table>
    <lg-toast ref="message"/>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import SubHeader from "./shared/SubHeader.vue";
import Pagination from "./helpers/Pagination.vue";
import {vMaska} from "maska"
import LgToast from "./helpers/Toast.vue";


export default {
    components: {
        LgToast,
        Pagination,
        SubHeader,
    },
    directives: {maska: vMaska},
    name: "Disputes",
    data() {
        return {
            options: {mask: '##.##.####'},
            checkboxes: [],
            files: [],
            filter: {
                type: null,
                date_from: null,
                date_to: null,
                card_from: null,
                card_to: null,
                uuid: null,
            },
            landscape: false,
            reactive: false,
            pagination: null,
        }
    },
    computed: {
        ...mapGetters([
            'GET_DISPUTES'
        ]),
    },
    mounted() {
        this.getData(1)
    },
    methods: {
        ...mapActions([
            'GET_DISPUTES'
        ]),
        async getData(page) {
            this.$store.dispatch('GET_DISPUTES', {page: page, data: this.filter})
                .then(response => this.pagination = response.pagination)
        },
    }
}
</script>

<style scoped>
.custom-radio {
    color: white;
}

.column-first {
    border-right: 1px solid rgba(243, 243, 243, 0.3);
}
</style>
