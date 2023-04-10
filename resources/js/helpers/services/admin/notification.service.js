import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const notificationService = {
    getNotification,
    send,
    deleteNotification
}

function getNotification() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/notification`, requestOptions)
        .then(handleResponse)
        .then(notification => {
            return notification;
        });
}

function send(notification) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(notification)
    };

    return fetch(`/api/admin/notification`, requestOptions)
        .then(handleResponse)
        .then(res => {
            return res;
        });
}

function deleteNotification(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/admin/notification/${id}`, requestOptions)
        .then(handleResponse)
        .then(res => {
            return res;
        });
}
