export default {
    LOGIN(state) {
        return state.login
    },
    TOKEN(state) {
        return state.token
    },
    ISUSERLOGGEDIN(state) {
        return state.isUserLoggedIn
    },
    GET_MERCHANTS(state) {
        return state.merchants
    },
    CURRENT_MERCHANT(state) {
        return state.current_merchant
    },
    GET_MY_TRANSACTIONS(state) {
        return state.my_transactions
    },
    GET_SUPPORT_REQUEST_CATEGORY(state) {
        return state.support_request_category
    },
    GET_MY_SUPPORT_REQUESTS(state) {
        return state.my_support_requests
    },
    GET_MY_WITHDRAWALS(state) {
        return state.my_withdrawals
    },
    GET_MY_BALANCE_RUB(state) {
        return state.my_balance_rub
    },
    GET_MY_BALANCE_USDT(state) {
        return state.my_balance_usdt
    },
    GET_MY_WITHDRAWALS_SUM(state) {
        return state.my_withdrawwals_sum
    },
    GET_MY_CREDIT_BALANCE_RUB(state) {
        return state.my_credit_balance_rub
    },
    user: state => state.user
}
