import {learnService} from '../../../services/home/learn.services';
export default {
    namespaced: true,
    state: {
        learnData: {},
    },
    mutations: {
        request(state) {
            state.learnData = {};
        },
        requestSuccess(state, data) {
            state.learnData = data;
        },
        requestFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        getFlashCard({commit}, id) {
            commit('request');
            learnService.loadFlashCard(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getLearn({commit}, id) {
            commit('request');
            learnService.loadLearn(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getTest({commit}, id) {
            commit('request');
            learnService.loadTest(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        getSelfTest({commit}, id) {
            commit('request');
            learnService.loadSelfTest(id)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        sendTestResult({commit}, data) {
            commit('request');
            learnService.sendTestResult(data)
                .then(
                    response => {
                        commit('requestSuccess', response);
                    },
                    error => {
                        commit('requestFailure', error);
                    }
                );
        },
        updateResult({commit}, data) {
            return learnService.updateResult(data)
                .then(data => {
                    console.log("data");
                        return data;
                    },
                    error => {
                        return null;
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
