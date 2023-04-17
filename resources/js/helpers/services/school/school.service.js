import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const schoolService = {
    get,
    update
}

function get(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/info/${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function update(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data.school)
    };
    return fetch(`/api/school/info/${data.slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
