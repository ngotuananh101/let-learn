// import router from "../../router";
import { dashboardService } from "../../services/dashboard.service";

const state = {
    quotes: null,
    analytics: null,
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        quotesRequest(state) {
            state.quotes = null;
        },
        setQuotes(state, quotes) {
            state.quotes = quotes;
        },
        quotesFailure(state) {
            state.quotes = null;
        },
        analyticsRequest(state) {
            state.analytics = null;
        },
        setAnalytics(state, analytics) {
            state.analytics = analytics;
        },
        analyticsFailure(state) {
            state.analytics = null;
        }
    },
    actions: {
        getQuotes({ commit }) {
            commit('quotesRequest');
            dashboardService.quotes()
                .then(
                    quotes => {
                        commit('setQuotes', quotes.data);
                    },
                    error => {
                        commit('quotesFailure');
                        dispatch('alert/success', user.message, { root: true });
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
        getAnalytics({ commit }) {
            commit('analyticsRequest');
            dashboardService.analytics()
                .then(
                    analytics => {
                        commit('setAnalytics', analytics.data);
                    },
                    error => {
                        commit('analyticsFailure');
                        dispatch('alert/success', user.message, { root: true });
                        dispatch('alert/error', error, { root: true });
                    }
                );
        }
    },
    getters: {
        quotes(state) {
            return state.quotes;
        }
    }
};
