import config from '../config';
import handleResponse from './handle-response';
import {authHeader} from '../auth-header';

export const learnService = {
    getLessons,
}

function getLessons(lesson_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.data.detail;
        });
}
