import {courseService} from '../../../services/school/course.service';

const state = {
    courses: [],
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        success(state, courses) {
            state.courses = courses;
        },
        failure(state) {
            state.dashboard = {};
        }
    },
    actions: {
        index({commit}, slug) {
            courseService.index(slug).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        store({commit}, data) {
            courseService.store(data).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        edit({commit}, id) {
            courseService.edit(id).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        addLessonToCourse({commit}, data) {
            courseService.addLessonToCourse(data).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        removeLessonFromCourse({commit}, data) {
            courseService.removeLessonFromCourse(data).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        updateCourse({commit}, data) {
            courseService.update(data).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        },
        deleteCourse({commit}, id) {
            courseService.deleteCourse(id).then(
                courses => {
                    commit('success', courses);
                },
                error => {
                    commit('failure', error);
                }
            );
        }
    }
}
