import {quizService} from '../../../services/class/quiz.service';

export default {
    namespaced: true,
    state: {
        quizzes: [],
    },
    mutations: {
        request(state) {
            state.quizzes = [];
        },
        failure(state, error) {
            state.quizzes = [];
        },
        quizAdded(state) {},
        setQuiz(state, quizzes) {
            state.quizzes = quizzes;
        },
        quizUpdated(state) {}
    },
    actions: {
        addQuiz({commit}, data) {
            commit('request');
            quizService.addQuiz(data)
                .then(
                    quiz => {
                        commit('quizAdded');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        getQuizByClassId({commit}, classId) {
            commit('request');
            quizService.getQuizByClassId(classId)
                .then(
                    quizzes => {
                        commit('setQuiz', quizzes);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        getQuizById({commit}, data) {
            commit('request');
            quizService.getQuizById(data.id,data.quiz_id)
                .then(
                    quiz => {
                        commit('setQuiz', quiz);
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        },
        updateQuiz({commit}, data) {
            commit('request');
            quizService.updateQuiz(data)
                .then(
                    res => {
                        commit('quizUpdated');
                    },
                    error => {
                        commit('failure', error);
                    }
                );
        }
    }
}
