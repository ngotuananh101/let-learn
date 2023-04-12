import {schoolService} from "../../../services/school/school.service";

export default {
    namespaced: true,
    state: {
        school: null,
    },
    getters: {
        school: state => state.school,
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
    },
    actions: {
        get({commit}, slug) {
            commit('request');
            schoolService.get(slug).then(
                school => {
                    commit('success', school);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        update({commit}, data) {
            commit('request');
            schoolService.update(data).then(
                school => {
                    commit('success', school);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    }
}
