import {dashboardService} from "../../../services/admin/dashboard.service";

const state = {
    quotes: null,
    analytics: null
};

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.quotes = null;
            state.analytics = null;
        },
        success(state, payload) {
            state.quotes = payload.quotes;
            state.analytics = payload.analytics;
        },
        failure(state) {
            state.quotes = null;
            state.analytics = null;
        }
    },
    actions: {
        get({commit}) {
            commit("request");
            try {
                Promise.allSettled([
                    dashboardService.quotes(),
                    dashboardService.analytics("duration"),
                    dashboardService.analytics("sessions"),
                    dashboardService.analytics("week"),
                    dashboardService.analytics("weekByEvent"),
                    dashboardService.analytics("local")
                ]).then((results) => {
                    if (results[0].status === "fulfilled" && results[1].status === "fulfilled" && results[2].status === "fulfilled" && results[3].status === "fulfilled" && results[4].status === "fulfilled" && results[5].status === "fulfilled") {
                        commit("success", {
                            quotes: results[0].value,
                            analytics: {
                                duration: results[1].value,
                                sessions: results[2].value,
                                week: results[3].value,
                                weekByEvent: results[4].value,
                                local: results[5].value
                            }
                        });
                    } else {
                        commit("failure");
                    }
                });
            } catch (error) {
                commit("failure");
            }
        },
    },
    getters: {
        quotes: state => state.quotes,
        analytics: state => state.analytics
    },
};
