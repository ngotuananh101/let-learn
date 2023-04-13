import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const userService = {
    show,
    store,
    update,
    destroy
}

function show(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/users/${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function store(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/users`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function update(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/users/${data.school_slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function destroy(user_id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/school/users/${user_id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
