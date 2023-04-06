import {classService} from '../../services/class.service';

let state = {status: {}, user: null};
export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = { indexing: true };
        },
        indexSuccess(state, folders) {
            state.status = { index: true };
            state.folders = folders;
        },
        indexFailure(state) {
            state.status = {};
            state.folders = [];
        }
    },
    actions: {
        getQuiz({commit}, id) {
            console.log(id);
            return classService.loadQuizByClassId(id)
                .then(
                    quiz => {
                        return Promise.resolve(quiz);
                    },
                );
        },
        doQuiz({commit}, id) {
            return classService.doQuiz(id)
                .then(doquiz => {
                    commit('setDoQuiz', doquiz);
                    return Promise.resolve(doquiz);
                });
        }
    }
}
