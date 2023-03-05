import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminSchoolService = {
    getAllSchools,
    addSchool
};

function getAllSchools() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`${config.apiUrl}/admin/school`, requestOptions).then(handleResponse).then(response => {
        return response.data;
    });
}

function addSchool(data) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    };
    return fetch(`${config.apiUrl}/admin/school`, requestOptions).then(handleResponse);
}
