import axios from "axios";

export default {
    SET_LOGIN({commit}, login) {
        commit('INSERT_LOGIN', login)
    },
    SET_TOKEN({commit}, token) {
        commit('INSERT_TOKEN', token)
    },
    DO_LOGOUT({commit}) {
        commit('LOGOUT')
    },

    async GET_MERCHANTS_FROM_API({commit}, page) {
        let dataPromise = await axios.get('/api/v1/merchant?page=' + page)
            .then(
                (response) => response.data
            );
        await commit('MY_MERCHANTS', dataPromise.data)
        return dataPromise
    },

    async GET_MERCHANT_DATA({commit}, uuid) {
        let dataPromise = await axios.get('/api/v1/merchant/' + uuid)
            .then(
                (response) => response.data
            );
        await commit('CURRENT_MERCHANT', dataPromise.data)
        return dataPromise
    },

    async GET_TRANSACTIONS_FROM_API({commit}, data) {
        let dataPromise = await axios.post('/api/v1/transactions?page='+data.page,data.data)
            .then(
                (response) => response.data
            );
        await commit('MY_TRANSACTIONS', dataPromise.data)
        return dataPromise
    },

    async GET_SUPPORT_REQUEST_CATEGORY_FROM_API({commit}) {
        let dataPromise = await axios.get('/api/v1/support-requests/categories')
            .then(
                (response) => response.data
            );
        await commit('SUPPORT_REQUEST_CATEGORY', dataPromise.data)
        return dataPromise
    },


    async GET_MY_SUPPORTS_REQUESTS_FROM_API({commit}) {
        let dataPromise = await axios.get('/api/v1/support-requests')
            .then(
                (response) => response.data
            );
        await commit('MY_SUPPORT_REQUESTS', dataPromise.data)
        return dataPromise
    },

    async GET_MY_WITHDRAWALS_API({commit}) {
        let dataPromise = await axios.get('/api/v1/withdrawal')
            .then(
                (response) => response.data
            );
        await commit('MY_WITHDRAWALS', dataPromise.data)
        console.log('dataPromise', dataPromise)
        return dataPromise
    },

    async GET_MY_BALANCE_FROM_API({commit}){
        let dataPromise = await axios.get('/api/v1/balance')
            .then(
                (response) => response.data
            );
        await commit('MY_BALANCE_RUB', dataPromise.data.balance.balance_rub)
        await commit('MY_BALANCE_USDT', dataPromise.data.balance.balance_usdt)
        return dataPromise
    },

    async GET_MY_WITHDRAWALS_SUM_FROM_API({commit}){
        let dataPromise = await axios.get('/api/v1/withdrawals-sum')
            .then(
                (response) => response.data
            );
        await commit('MY_WITHDRAWALS_SUM', dataPromise.data)
        return dataPromise
    }

}
