import config from '../config';
import {authHeader} from '../auth-header';
import handleResponse from './handle-response';


export const homeService = {
    loadLesson,
    loadCourse,
    getLessonByCourseId,
    showLessonDetailRelearn,
    showLessonDetailNotLearn,
    getLessonInfo
}

function loadLesson() {
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=lesson&limit=6`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function loadCourse() {
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=course&limit=6`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getLessonByCourseId(course_id) {
    console.log(course_id);
    return fetch(`${config.apiUrl}/course/${course_id}/lesson`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

//show lesson detail of relearn
function showLessonDetailRelearn(lesson_id){
    console.log(lesson_id);
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=detail_split&lesson_id=${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.relearn;
        }
    );
}

//show lesson detail of not learn
function showLessonDetailNotLearn(lesson_id){
    console.log(lesson_id);
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=detail_split&lesson_id=${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.notLearn;
        }
    );
}

//show lesson info
function getLessonInfo(lesson_id) {
    console.log(lesson_id);
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data.lesson;
        });
}


