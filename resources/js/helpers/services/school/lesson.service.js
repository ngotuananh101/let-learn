import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const lessonService = {
    index,
    add,
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/${slug}/lesson`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function add(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/lessons`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
