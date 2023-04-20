import {classService} from '../../../services/school/class.service';

const state = {
    classes: [],
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        success(state, classes) {
            state.classes = classes;
        },
        failure(state) {
            state.dashboard = {};
        },
        request(state) {
            state.classes = [];
        }
    },
    actions: {
        index({commit}, slug) {
            commit('request');
            classService.index(slug).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        show({commit}, id) {
            commit('request');
            classService.show(id).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        update({commit}, data) {
            commit('request');
            classService.update(data.id, data.data).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        getQuizById({commit}, data) {
            commit('request');
            classService.getQuizById(data.quiz_id, data.class_id).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        updateQuiz({commit}, data) {
            commit('request');
            classService.updateQuiz(data.id, data.data).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        getPostById({commit}, data) {
            commit('request');
            classService.getPostById(data.post_id, data.class_id, data.page).then(
                classes => {
                    commit('success', classes);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    }
}
