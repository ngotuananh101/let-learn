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

    return fetch(`/api/user/course/${course_id}?type=info`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            console.log(data.course);
            return data.course;
        });
}

function addCourse(course) {
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

