import {userService} from "../../../services/school/user.service";

export default {
    namespaced: true,
    state: {
        data: {},
    },
    actions: {
        show({commit}, slug) {
            commit('request');
            userService.show(slug)
                .then(
                    dashboard => commit('success', dashboard),
                    error => commit('failure', error)
                );
        },
        store({commit}, data) {
            commit('request');
            userService.store(data)
                .then(
                    dashboard => commit('success', dashboard),
                    error => commit('failure', error)
                );
        },
        update({commit}, data) {
            commit('request');
            userService.update(data)
                .then(
                    dashboard => commit('success', dashboard),
                    error => commit('failure', error)
                );
        },
        destroy({commit}, user_id) {
            commit('request');
            userService.destroy(user_id)
                .then(
                    dashboard => commit('success', dashboard),
                    error => commit('failure', error)
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
