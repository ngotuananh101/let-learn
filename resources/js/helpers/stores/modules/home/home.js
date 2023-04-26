import {homeService} from '../../../services/home/home.services';
import {learnService} from "@/helpers/services/home/learn.services";
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
        search(state, data) {},
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
        getUserInformation({commit}, page) {
            commit('request');
            homeService.loadUserInformation(page)
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
            homeService.loadClassDetail(payload.id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        changeInfor({ commit }, data) {
            commit('request');
            homeService.changeInfor(data)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        updatePassword({ commit }, password){
            console.log(password);
            commit("request");
            homeService.updatePassword(password).then(
                (password) => {
                    commit("requestSuccess", password);
                },
                (error) => {
                    commit("requestFailure", error);
                }
            );
        },
        search({ commit }, query) {
            commit('request');
            homeService.search(query)
                .then(
                    response => {
                        commit('search', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        }

    },
    getters: {
        userData: state => {
            return state.userData;
        }
    }
};
