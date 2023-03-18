import config from '../config';
import { authHeader } from '../auth-header';
import handleResponse from './handle-response';


export const homeService = {
    loadLesson,
    loadCourse,
<<<<<<< HEAD
    // loadLessonDetailByLessonId,
    // getLessonByCourseID
=======
    getLessonByCourseId
>>>>>>> 1d11a814c3cc9f62259f7a4a52f699969a25ce50
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
<<<<<<< HEAD

// function loadLessonDetailByLessonId(lesson_id) {
//     let lesson = JSON.parse(localStorage.getItem('lesson'));
//     let lesson_id = lesson.id;
//     return fetch(`${config.apiUrl}/lesson/${lesson_id}`, { method: 'GET', headers: authHeader() })
//         .then(handleResponse)
//         .then(data => {
//             return data.data;
//         });
// }

function getLessonByCourseID(course_id) {
    // let course = JSON.parse(localStorage.getItem('course'));
    // let course_id = course.id;
=======
// function get lessons by course id
function getLessonByCourseId(course_id) {
    console.log(course_id);
>>>>>>> 1d11a814c3cc9f62259f7a4a52f699969a25ce50
    return fetch(`${config.apiUrl}/course/${course_id}/lesson`, { method: 'GET', headers: authHeader() })
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

