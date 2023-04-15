import {homeService} from '../../../services/home/home.services';
export default {
    namespaced: true,
    state: {
        homeData: {},
    },
    mutations: {
        request(state) {
            state.homeData = {};
        },
        requestSuccess(state, data) {
            state.homeData = data;
        },
        requestFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        getHome({commit}) {
            commit('request');
            homeService.loadHome()
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getUserInformation({commit}) {
            commit('request');
            homeService.loadUserInformation()
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getClassDetail({ commit }, payload) {
            commit('request');
            console.log(payload.id, payload.roleName);
            homeService.loadClassDetail(payload.id, payload.roleName)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },

    },
    getters: {
        userData: state => {
            return state.userData;
        }
    }
};
