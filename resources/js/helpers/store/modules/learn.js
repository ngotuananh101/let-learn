import {learnService} from "@/helpers/services/learn.service";

const state = {
    data: [],
    loading: false,
    error: null,
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        getLessonsRequest(state) {
            state.loading = true
        },
        getLessonsSuccess(state, lessons) {
            state.data = lessons
            state.loading = false
        },
        getLessonsFailure(state, error) {
            state.error = error
            state.loading = false
        }
    },
    actions: {
        getLessons({commit}, id) {
            commit('getLessonsRequest')
            return learnService.getLessons(id);
        }
    },
    getters: {
        lessons(state) {
            return state.data
        },
        loading(state) {
            return state.loading
        },
        error(state) {
            return state.error
        }
    }
}

