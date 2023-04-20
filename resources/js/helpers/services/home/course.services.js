import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";

export const courseService = {
    getLessonByCourseId,
    addCourse,
    updateCourse,
    deleteCourse,
};

function getLessonByCourseId(id, roleName) {
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

function deleteCourse(data) {
    const requestOptions = {
        method: "DELETE",
        headers: authHeader(),
    };
console.log(data);
    return fetch(`/api/${data.roleName}/course/${data.id}`, requestOptions)
        .then(handleResponse)
        .then((course) => {
            return course;
        });
}
