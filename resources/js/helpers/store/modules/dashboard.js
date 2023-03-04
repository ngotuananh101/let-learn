// import router from "../../router";
import { dashboardService } from "../../services/dashboard.service";

const state = {
    quotes: null,
    analytics: {
        basic: null,
        averageSessionDuration: null,
        browser: null,
        country: null
    }
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
            state.analytics.basic = null;
            state.analytics.averageSessionDuration = null;
            state.analytics.browser = null;
            state.analytics.country = null;
        },
        setAnalytics(state, analytics) {
            state.analytics.basic = null;
            state.analytics.averageSessionDuration = {
                date: [],
                value: []
            };
            for (let index = 0; index < analytics.averageSessionDuration.length; index++) {
                const element = analytics.averageSessionDuration[index];
                let date = element.day + '/' + element.month + '/' + element.year;
                let value = element.averageSessionDuration;
                state.analytics.averageSessionDuration.date.push(date);
                state.analytics.averageSessionDuration.value.push(value);
            }
            state.analytics.browser = analytics.browser;
            state.analytics.country = analytics.country;
        },
        analyticsFailure(state) {
            state.analytics.basic = null;
            state.analytics.averageSessionDuration = null;
            state.analytics.browser = null;
            state.analytics.country = null;
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
                        dispatch('alert/error', error, { root: true });
                    }
                );
        },
        getAnalytics({ commit }) {
            commit('analyticsRequest');
            dashboardService.analytics()
                .then(
                    analytics => {
                        commit('setAnalytics', analytics);
                    },
                    error => {
                        commit('analyticsFailure');
                        dispatch('alert/error', error, { root: true });
                    }
                );
        }
    },
    getters: {
        quotes(state) {
            return state.quotes;
        },
        analytics(state) {
            return state.analytics;
        }
    }
};
