import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminCourseService = {
    index,
    getCourse,
    updateCourse,
    searchLesson
}

function index() {
    return fetch(`${config.apiUrl}/admin/course`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function getCourse(course_id) {
    return fetch(`${config.apiUrl}/admin/course/${course_id}/edit`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function updateCourse(course) {
    course.type = 'course';
    return fetch(`${config.apiUrl}/admin/course/${course.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(course)
    }).then(handleResponse);
}

function searchLesson(keyword) {
    return fetch(`${config.apiUrl}/admin/course/${keyword}?type=find_lesson`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}
