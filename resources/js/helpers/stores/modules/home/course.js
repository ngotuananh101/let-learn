import { courseService } from "../../../services/home/course.services";

const state = {
    data: [],
};

export default {
    namespaced: true,
    state: state,
    getters: {
        data: (state) => state.data,
    },
    mutations: {
        request(state) {
            state.data = [];
        },
        success(state, payload) {
            state.data = payload;
        },
        failure(state, payload) {
            state.data = [];
        },
    },
    actions: {
        getCourseInfo({ commit }, course_id) {
            commit("request");
            courseService.getCourseInfo(course_id).then(
                (course) => {
                    commit("success", course);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
        getLessonByCourseId({ commit }, course_id) {
            commit("request");
            courseService.getLessonByCourseId(course_id).then(
                (lessons) => {
                    commit("success", lessons);
                },
                (error) => {
                    commit("failure", error);
                }
            );
        },
    },
};
