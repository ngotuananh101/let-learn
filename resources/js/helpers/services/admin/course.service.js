import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const courseService = {
    getAll,
    add,
    getCourseById,
    updateCourse,
    deleteCourse,
    searchLesson,
    addLessonToCourse,
    removeLessonFromCourse
}

function getAll() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/course`, requestOptions).then(handleResponse).then(
        courses => {
            return courses;
        }
    )
}

function add(course) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(course)
    };

    return fetch(`/api/admin/course`, requestOptions).then(handleResponse).then(
        course => {
            return course;
        }
    )
}

function getCourseById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/course/${id}?type=info`, requestOptions).then(handleResponse).then(
        course => {
            return course;
        }
    )
}

function updateCourse(course) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(course)
    };

    return fetch(`/api/admin/course/${course.id}`, requestOptions).then(handleResponse).then(
        course => {
            return course;
        }
    )
}

function searchLesson(keyword) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/course/0?type=lessons&keyword=${keyword}`, requestOptions).then(handleResponse).then(
        lesson => {
            return lesson;
        }
    )
}

function addLessonToCourse(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/admin/course/${data.id}`, requestOptions).then(handleResponse).then(
        lesson => {
            return lesson;
        }
    )
}

function deleteCourse(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/admin/course/${id}`, requestOptions).then(handleResponse).then(
        course => {
            return course;
        }
    )
}

function removeLessonFromCourse(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };

    return fetch(`/api/admin/course/${data.id}`, requestOptions).then(handleResponse).then(
        lesson => {
            return lesson;
        }
    )
}
