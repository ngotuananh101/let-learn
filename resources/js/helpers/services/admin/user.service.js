import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const userService = {
    getUserList,
    addUser,
    updateUser,
    deleteUser
}

function getUserList() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/user?type=all`, requestOptions)
        .then(handleResponse)
}

function addUser(user) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(user)
    };
    return fetch(`/api/admin/user`, requestOptions)
        .then(handleResponse)
}

function updateUser(user) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(user)
    };
    return fetch(`/api/admin/user/${user.id}`, requestOptions)
        .then(handleResponse)
}

function deleteUser(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/admin/user/${id}`, requestOptions)
        .then(handleResponse)
}
