import config from '../config';
import {authHeader} from '../auth-header';
import handleResponse from './handle-response';

export const classService = {
    loadQuizByClassId,
    doQuiz,
}
const user = JSON.parse(localStorage.getItem('user'));
function loadQuizByClassId(class_id) {
    return fetch(`${config.apiUrl}/quiz/1?type=all&class_id=${class_id}`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data.data;
        });
}

function doQuiz(quiz_id) {
    return fetch(`${config.apiUrl}/quiz/${quiz_id}?type=question`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data.data;
        });
}
