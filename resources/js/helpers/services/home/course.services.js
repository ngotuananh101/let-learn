import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";

export const courseService = {
    getLessonByCourseId,
    createCourse,
    updateCourse,
    deleteCourse,
    addLessonToCourse,
    removeLessonFromCourse,
    searchLesson,
};

function getLessonByCourseId(course_id) {
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };

    return fetch(`/api/user/course/${course_id}?type=info`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data;
        });
}

function createCourse(course) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(course),
    };

    return fetch(`/api/user/course`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}

function updateCourse(course) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify(course),
    };

    return fetch(`/api/user/course/${course.id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}

function searchLesson(keyword) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/user/course/0?type=lessons&keyword=${keyword}`, requestOptions).then(handleResponse).then(
        lesson => {
            return lesson;
        }
    )
}

function addLessonToCourse(data) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify(data),
    };

    return fetch(`/api/user/course/${data.id}`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}

function deleteCourse(id) {
    const requestOptions = {
        method: "DELETE",
        headers: authHeader(),
    };

    return fetch(`/api/user/course/${id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}

function removeLessonFromCourse(data) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify(data),
    };

    return fetch(`/api/user/course/${data.id}`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}
