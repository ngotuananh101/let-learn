import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const courseService = {
    index,
    store,
    edit,
    addLessonToCourse,
    removeLessonFromCourse,
    update,
    deleteCourse
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/courses/${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function store(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/courses`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function edit(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/courses/${id}/edit`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function addLessonToCourse(data) {
    data.type = 'add-lesson';
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/courses/${data.id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function removeLessonFromCourse(data) {
    data.type = 'remove-lesson';
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/courses/${data.id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function update(data) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`/api/school/courses/${data.id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function deleteCourse(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };
    return fetch(`/api/school/courses/${id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
