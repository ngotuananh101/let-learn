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
        }
    }
}
