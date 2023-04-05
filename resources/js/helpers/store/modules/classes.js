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
        getQuiz() {
            return classService.loadQuizByClassId()
                .then(
                    quiz => {
                        return Promise.resolve(quiz);
                    },
                );
        },
    }
}
