import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';


export const homeService = {
    loadLesson,
    loadCourse,
    getLessonByCourseId,
    showLessonDetailRelearn,
    showLessonDetailNotLearn,
    getLessonInfo,
    createLesson,
    updateLesson,
    deleteLesson,
    createCourse,
    deleteCourse,
    getCourseInfo,
    updateCourse
}

function loadLesson() {
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=lesson&limit=6`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function loadCourse() {
    let user = JSON.parse(localStorage.getItem('user'));
    let user_id = user.id;
    return fetch(`${config.apiUrl}/user/${user_id}?type=course&limit=6`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getLessonByCourseId(course_id) {
    console.log(course_id);
    return fetch(`${config.apiUrl}/course/${course_id}/lesson`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

//show lesson detail of relearn
function showLessonDetailRelearn(lesson_id) {
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
function showLessonDetailNotLearn(lesson_id) {
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
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data.lesson;
        });
}
//create new lesson
function createLesson(data) {
    data.type = 'general';
    return fetch(`${config.apiUrl}/lesson`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//update lesson
function updateLesson(data) {
    return fetch(`${config.apiUrl}/lesson/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//delete lesson by lesson id
function deleteLesson(lesson_id) {
    return fetch(`${config.apiUrl}/lesson/${lesson_id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}
//create new course
function createCourse(data) {
    return fetch(`${config.apiUrl}/course`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}
//delete course by course id
function deleteCourse(course_id) {
    return fetch(`${config.apiUrl}/course/${course_id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}
//get course from database by course id
function getCourseInfo(course_id) {
    return fetch(`${config.apiUrl}/course/${course_id}`, {
        method: 'GET',
        headers: authHeader()
    }).then(handleResponse).then(data => {
        return data.data;
    });
}
//update course
function updateCourse(data) {
    return fetch(`${config.apiUrl}/course/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

