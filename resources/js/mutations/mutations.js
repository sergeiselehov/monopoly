export default {
    /*
  INSERT_LOGIN: (state, login)=>{
    window.localStorage.setItem('login',login)
    window.localStorage.setItem('isUserLoggedIn','1')
    state.isUserLoggedIn = 1
    state.login = login
  },
  INSERT_TOKEN: (state, token)=>{
    window.localStorage.setItem('token',token)
    window.localStorage.setItem('isUserLoggedIn','1')
    state.isUserLoggedIn = 1
    state.token = token

  },
  PICTURES: (state, pictures)=>{
    state.pictures = pictures
  },
  LOGOUT:(state)=>{
    window.localStorage.removeItem('token')
    window.localStorage.removeItem('isUserLoggedIn')
    window.localStorage.removeItem('login')
    state.isUserLoggedIn = 0
    state.token = ''
    state.login = ''
  },*/
    setUser(state, userData) {
        state.user = userData
    },
    MY_MERCHANTS: (state, data)=>{
        state.merchants = data
    },
    CURRENT_MERCHANT: (state, data)=>{
        state.current_merchant = data.data
    },
    MY_TRANSACTIONS: (state, data)=>{
        state.my_transactions = data
    },
    SUPPORT_REQUEST_CATEGORY:(state, data)=>{
        state.support_request_category = data
    },
    MY_SUPPORT_REQUESTS:(state, data)=>{
        state.my_support_requests = data
    },
    MY_WITHDRAWALS:(state, data)=>{
        state.my_withdrawals = data
    },
    MY_BALANCE_RUB:(state, data)=>{
        state.my_balance_rub = data
    },
    MY_BALANCE_USDT:(state, data)=>{
        state.my_balance_usdt = data
    },
    MY_WITHDRAWALS_SUM:(state, data)=>{
        state.my_withdrawals_sum = data
    },

}
