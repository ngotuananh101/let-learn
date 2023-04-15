import {lessonService} from '../../../services/school/lesson.service'

const state = {
    lessons: [],
}

export default {
    namespaced: true,
    state: state,
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
        index({commit}, slug) {
            commit('request');
            lessonService.index(slug).then(
                lessons => {
                    commit('success', lessons);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        add({commit}, data) {
            commit('request');
            lessonService.add(data).then(
                lesson => {
                    commit('success', lesson);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    }
}
