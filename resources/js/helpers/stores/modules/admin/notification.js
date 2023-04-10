import {notificationService} from "../../../services/admin/notification.service";

const state = {
    notification: null
}

export default {
    namespaced: true,
    state: state,
    mutations: {
        request(state) {
            state.notification = null;
        },
        success(state, payload) {
            state.notification = payload;
        },
        failure(state) {
            state.notification = null;
        }
    },
    actions: {
        getNotification({commit}) {
            commit('request');
            notificationService.getNotification()
                .then(
                    response => {
                        commit('success', response.notifications);
                    },
                    error => {
                        commit('failure');
                    }
                );
        },
        sendNotification({commit}, notification) {
            commit('request');
            // convert datetime-local to UCT
            notification.schedule = notification.schedule ? new Date(notification.schedule).toUTCString() : "";
            notificationService.send(notification)
                .then(
                    notification => {
                        commit('success', notification);
                    },
                    error => {
                        commit('failure');
                    }
                );
        },
        deleteNotification({commit}, id) {
            commit('request');
            notificationService.deleteNotification(id)
                .then(
                    response => {
                        commit('success', response);
                    },
                    error => {
                        commit('failure');
                    }
                );
        }
    }
}
