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
        },
        createLesson({dispatch}, data) {
            homeService.createLesson(data)
                .then(
                    lessons => {
                        dispatch('alert/success', 'Lesson created', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        getLessonInfo({dispatch}, lesson_id) {
            return homeService.getLessonInfo(lesson_id)
                .then(
                    data => {
                        return data;
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateLesson({dispatch}, data) {
            homeService.updateLesson(data)
                .then(
                    lessons => {
                        dispatch('alert/success', 'Lesson updated', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        deleteLesson({dispatch}, lesson_id) {
            homeService.deleteSet(lesson_id)
                .then(
                    lessons => {
                        dispatch('alert/success', 'Lesson deleted', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        createCourse({dispatch}, data) {
            homeService.createCourse(data)
                .then(
                    courses => {
                        dispatch('alert/success', 'Course created', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        deleteCourse({dispatch}, course_id) {
            homeService.deleteCourse(course_id)
                .then(
                    courses => {
                        dispatch('alert/success', 'Course deleted', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        getCourseInfo({dispatch}, course_id) {
            return homeService.getCourseInfo(course_id)
                .then(
                    courses => {
                        return courses;
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateCourse({dispatch}, data) {
            homeService.updateCourse(data)
                .then(
                    courses => {
                        dispatch('alert/success', 'Course updated', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
    }
}
