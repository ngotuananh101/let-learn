import {adminSetService} from '../../../services/admin/set.service';
import overlay from '../../../overlay';

let sets = JSON.parse(localStorage.getItem('set'));
let state = sets ? {status: {index: true}, sets: sets} : {status: {}, sets: []};

export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, sets) {
            state.status = {index: true};
            state.sets = sets;
        },
        indexFailure(state) {
            state.status = {};
            state.sets = [];
        }
    },
    actions: {
        index({commit}) {
            commit('indexRequest');
            adminSetService.index()
                .then(
                    sets => {
                        commit('indexSuccess', sets);
                    },
                    error => {
                        commit('indexFailure', error);
                    }
                );
        },
        create({dispatch}, data) {
            adminSetService.create(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson created', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        importSet({dispatch}, data) {
            dispatch('alert/info', 'Lesson importing', {root: true});
            overlay();
            adminSetService.importSet(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson imported', {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                ).then(() => {
                    overlay();
                }
            );
        },
        deleteSet({dispatch}, id) {
            adminSetService.deleteSet(id)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson deleted', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        },
        exportSet({dispatch}, id) {
            dispatch('alert/info', 'Lesson exporting', {root: true});
            overlay();
            adminSetService.exportSet(id)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson exported', {root: true});
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                ).then(() => {
                    overlay();
                }
            );
        },
        getSet({dispatch}, id) {
            overlay();
            return adminSetService.getSet(id)
                .then(
                    sets => {
                        overlay();
                        return sets;
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                        // return back to previous page
                        window.history.back();
                    }
                );
        },
        updateSet({dispatch}, data) {
            adminSetService.updateSet(data)
                .then(
                    sets => {
                        dispatch('alert/success', 'Lesson updated', {root: true});
                        dispatch('index');
                    },
                    error => {
                        dispatch('alert/error', error, {root: true});
                    }
                );
        }
    },
    getters: {
        sets: state => {
            return state.sets;
        }
    }
}
