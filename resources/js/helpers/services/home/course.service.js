import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const courseService = {
    getLessonByCourseId,
    // createCourse,
    // deleteCourse,
    getCourseInfo,
    // updateCourse,
}

function getCourseInfo(course_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/user/course/${course_id}`, requestOptions)
        .then(handleResponse)
        .then(course => {
            return course;
        });
}

function getLessonByCourseId(course_id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/user/course/${course_id}`, requestOptions)
        .then(handleResponse)
        .then(lessons => {
            return lessons;
        });
}

