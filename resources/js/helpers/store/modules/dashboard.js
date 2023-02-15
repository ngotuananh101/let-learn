// import router from "../../router";

const state = {
    quotes: null,
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        setQuotes(state, quotes) {
            state.quotes = quotes;
        },
    },
    actions: {
        getQuotes({ commit }) {
            fetch('https://quote-garden.onrender.com/api/v3/quotes?limit=3')
                .then(handleResponse)
                .then(quotes => {
                    commit('setQuotes', quotes.data);
                },
                error => {
                    console.log(error);
                }
            );
        },
    },
    getters: {
        quotes(state) {
            return state.quotes;
        }
    }
}

function handleResponse(response) {
    return response.text().then(text => {
        const data = text && JSON.parse(text);
        if (!response.ok) {
            const error = (data && data.message) || response.statusText;
            return Promise.reject(error);
        }
        return data;
    });
}
