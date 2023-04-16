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
        getForumDetail({commit}, id) {
            commit('request');
            homeService.loadForumDetail(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getForum({commit}) {
            commit('request');
            homeService.loadForum()
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        addQuestionForum({commit}, data) {
            commit('request');
            homeService.AddQuestionForum(data)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        commentForum({commit}, data) {
            commit('request');
            homeService.commentForum(data)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        voteComment({commit}, data) {
            commit('request');
            homeService.voteComment(data)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        voteQuestion({commit}, data) {
            commit('request');
            homeService.voteQuestion(data)
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
