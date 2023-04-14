import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';


export const homeService = {
    loadHome,
    loadClassDetail,
    loadLessonByUserId,
    loadCourseByUserId
};

function loadHome() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/public/home`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data);
            return data;
        });
};
function loadLessonByUserId(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/student/main/${id}?type=lesson`, requestOptions).then(handleResponse).then(
        data => {
            console.log(data);
            return data.data;
        }
    )
}
function loadCourseByUserId(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/student/main/${id}?type=course`, requestOptions).then(handleResponse).then(
        data => {
            console.log(data);
            return data.data;
        }
    )
}
function loadClassDetail(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
    };
    return fetch(`/api/student/quiz/${id}?type=all`, requestOptions)
        .then(handleResponse)
        .then(data => {
            console.log(data.data);
            return data.data;
        });
}

