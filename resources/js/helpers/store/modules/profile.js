import {profileService} from '../../services/profile.service';

let state = {status: {}, user: null};
export default {
    namespaced: true,
    state: state,
    mutations: {
        indexRequest(state) {
            state.status = {indexing: true};
        },
        indexSuccess(state, folders) {
            state.status = {index: true};
            state.folders = folders;
        },
        indexFailure(state) {
            state.status = {};
            state.folders = [];
        }
    },
    actions: {
        getUserInfor() {
            return profileService.loadUserInfor()
                .then(
                    user => {
                        return Promise.resolve(user);
                    },
                );
        },
        getLesson() {
            return profileService.loadLesson()
                .then(
                    lessons => {
                        return Promise.resolve(lessons);
                    },
                );
        },
        get

    }
}
