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
        getLessonByUserId({commit}, id) {
            commit('request');
            console.log(id);
            homeService.loadLessonByUserId(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getCourseByUserId({commit}, id) {
            commit('request');
            console.log(id);
            homeService.loadCourseByUserId(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getClassDetail({commit}, id) {
            commit('request');
            console.log(id);
            homeService.loadClassDetail(id)
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
