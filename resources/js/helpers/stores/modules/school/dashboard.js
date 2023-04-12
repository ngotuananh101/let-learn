import {dashboardService} from "../../../services/school/dashboard.service";

export default {
    namespaced: true,
    state: {
        dashboard: {},
    },
    actions: {
        getInfo({commit}, slug) {
            commit('request');
            dashboardService.baseInfo(slug).then(
                dashboard => {
                    commit('success', dashboard);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        getWeather({commit}) {
            commit('request');
            dashboardService.weather().then(
                dashboard => {
                    commit('success', dashboard);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    },
    mutations: {
        success(state, dashboard) {
            state.dashboard = dashboard;
        },
        failure(state) {
            state.dashboard = {};
        },
        request(state) {
            state.dashboard = {};
        }
    }
}
