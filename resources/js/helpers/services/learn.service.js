import config from '../config';
import handleResponse from './handle-response';
import {authHeader} from '../auth-header';

export const learnService = {
    getLessons,
    getLearn
}
const user = JSON.parse(localStorage.getItem('user'));

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

function getLearn(lesson_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`${config.apiUrl}/user/${user.id}/?type=learn&lesson_id=${lesson_id}&reverse=true&mix_answers=true&mix_details=true`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data.lesson_details;
        });
}
