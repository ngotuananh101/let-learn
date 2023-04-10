import {schoolService} from '../../../services/admin/school.service';

const state = {
    schools: null,
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.schools = null;
        },
        success(state, payload) {
            state.schools = payload;
        },
        failure(state, payload) {
            state.schools = null;
        }
    },
    actions: {
        getSchoolList({commit}) {
            commit('request');
            schoolService.getSchoolList()
                .then(
                    schools => {
                        commit('success', schools);
                    },
                    error => {
                        commit('failure');
                    }
                );
        },
        addSchool({commit}, payload) {
            commit('request');
            schoolService.addSchool(payload)
                .then(
                    schools => {
                        commit('success', schools);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        deleteSchool({commit}, payload) {
            commit('request');
            schoolService.deleteSchool(payload)
                .then(
                    schools => {
                        commit('success', schools);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        getSchoolById({commit}, payload) {
            commit('request');
            schoolService.getSchoolById(payload)
                .then(
                    school => {
                        commit('success', school);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        updateSchool({commit}, payload) {
            commit('request');
            schoolService.updateSchool(payload)
                .then(
                    school => {
                        commit('success', school);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        }
    },
}
