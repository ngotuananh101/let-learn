import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";

export const courseService = {
    getLessonByCourseId,
    addCourse,
    updateCourse,
    deleteCourse,
};

function getLessonByCourseId(id, roleName) {
    console.log(id, roleName);
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };

    return fetch(`/api/${roleName}/course/${id}?type=info`, requestOptions)
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

    return fetch(`/api/${course.roleName}/course`, requestOptions)
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

    return fetch(`/api/${course.roleName}/course/${course.id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}

function deleteCourse(id, roleName) {
    const requestOptions = {
        method: "DELETE",
        headers: authHeader(),
    };

    return fetch(`/api/${roleName}/course/${id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}
