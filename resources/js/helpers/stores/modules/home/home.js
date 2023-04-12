import {homeService} from '../../../services/home/home.services';
import {notificationService} from "@/helpers/services/admin/notification.service";
import handleResponse from "@/helpers/other/handle-response";

function getUser() {
    let userData = localStorage.getItem('userData');
    if (userData) {
        return JSON.parse(userData);
    } else {
        return null;
    }
}
const userData = getUser();
let state = {
    status: {loggedIn: !!userData},
    userData: userData
};
export default {
    namespaced: true,
    state: state,
    mutations: {
        requestSuccess(state, data) {
            state.status = {};
        },
        requestFailure(state, error) {
            state.status = {};
        },
    },
    actions: {
        getHome() {
            return homeService.loadHome()
                .then(
                    response => {
                        return response;
                    },
                    error => {
                        return error;
                    }
                );
        },

    },
    getters: {
        userData: state => {
            return state.userData;
        }
    }
};
