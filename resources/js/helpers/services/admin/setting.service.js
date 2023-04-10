import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';

export const settingService = {
    get,
    update
}

function get() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/admin/setting`, requestOptions)
        .then(handleResponse)
}

function update(setting) {
    // remove unused fields
    delete setting.timezone;
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(setting)
    };
    return fetch(`/api/admin/setting/0`, requestOptions)
        .then(handleResponse)
}
