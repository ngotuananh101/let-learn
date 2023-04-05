import config from '../config';
import {authHeader} from '../auth-header';
import handleResponse from './handle-response';

export const classService = {
    loadQuizByClassId,
}

function loadQuizByClassId() {
    return fetch(`${config.apiUrl}/quiz/${1}?type=quiz`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
