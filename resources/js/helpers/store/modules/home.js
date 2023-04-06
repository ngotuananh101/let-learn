import { homeService } from '../../services/home.service';
import lesson from './admin/lesson';

let state = { status: {}, user: null };
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
        getLesson() {
            return homeService.loadLesson()
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    },
                );
        },
        getCourse() {
            return homeService.loadCourse()
                .then(
                    course => {
                        return Promise.resolve(course);
                    },
                );
        },
        getQuiz({commit}, id) {
            console.log(id);
            return homeService.loadQuizByClassId(id)
                .then(
                    quiz => {
                        return Promise.resolve(quiz);
                    },
                );
        },

    }
}
