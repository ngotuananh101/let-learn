import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";

export const courseService = {
    getLessonByCourseId,
    addCourse,
    updateCourse,
    deleteCourse,
};

function getLessonByCourseId(course_id) {
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };

    return fetch(`/api/student/course/${course_id}?type=info`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data;
        });
}

function addCourse(course) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(course),
    };

    return fetch(`/api/student/course`, requestOptions)
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

    return fetch(`/api/student/course/${course.id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}

function deleteCourse(id) {
    const requestOptions = {
        method: "DELETE",
        headers: authHeader(),
    };

    return fetch(`/api/student/course/${id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}
