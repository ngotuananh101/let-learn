import { homeService } from '../../services/home.service';

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

        getLessonByCourseId({ commit }, course_id) {
            return homeService.getLessonByCourseId(course_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    }
                );
        },
        getLessonDetailRelearn({ commit }, lesson_id) {
            return homeService.showLessonDetailRelearn(lesson_id)
                .then(
                    relearn => {
                        return Promise.resolve(relearn);
                    }
                );
        },
        getLessonDetailNotLearn({ commit }, lesson_id) {
            return homeService.showLessonDetailNotLearn(lesson_id)
                .then(
                    notlearn => {
                        return Promise.resolve(notlearn);
                    }
                );
        },
        getLessonInfo({ commit }, lesson_id) {
            return homeService.getLessonInfo(lesson_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    }
                );
        }

    }
}
