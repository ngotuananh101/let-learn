import {adminSchoolService} from '../../../services/admin/school.service';
import overlay from '../../../overlay';

const state = {
    status: {},
    schools: [],
}

export default {
    namespaced: true,
    state,
    actions: {
        getAllSchools({commit}) {
            return adminSchoolService.getAllSchools()
                .then(
                    schools => {
                        commit('getSchools', schools);
                        return Promise.resolve(schools);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
        addSchool({commit}, data) {
            overlay();
            return adminSchoolService.addSchool(data)
                .then(
                    school => {
                        commit('getSchools', school);
                        overlay();
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        overlay();
                        return Promise.reject(error);
                    }
                );
        },
        getSchool({commit}, id) {
            return adminSchoolService.getSchool(id)
                .then(
                    school => {
                        commit('getSchools', school);
                        return Promise.resolve(school);
                    },
                    error => {
                        commit('getSchools', null);
                        return Promise.reject(error);
                    }
                );
        },
    },
    mutations: {
        getSchools(state, schools) {
            state.schools = schools;
        },
    }
}
