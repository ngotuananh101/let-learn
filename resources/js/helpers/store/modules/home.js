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
        // Get lesson detail by lesson_id
        getLessonDetail(lesson_id) {
            return homeService.loadLessonDetailByLessonId(lesson_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    },
                );
        },
        // Get lesson by course_id
        getLessonByCourseID(course_id) {
            return homeService.getLessonByCourseID(course_id)
                .then(
                    lesson => {
                        return Promise.resolve(lesson);
                    }
                );
        }
    }
}
