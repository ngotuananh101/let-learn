import config from '../../config';
import {authHeader} from '../../auth-header';
import handleResponse from './../handle-response';

export const adminSetService = {
    index,
    create,
    importSet,
    deleteSet,
    exportSet,
    getSet,
    updateSet
}

function index() {
    return fetch(`${config.apiUrl}/admin/lesson`, {method: 'GET', headers: authHeader()})
        .then(handleResponse)
        .then(data => {
            return data.data;
        });
}

function create(data) {
    data.type = 'general';
    return fetch(`${config.apiUrl}/admin/set`, {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

function importSet(data) {
    let headers = authHeader();
    delete headers['Content-Type'];
    return fetch(`${config.apiUrl}/admin/set`, {
        method: 'POST',
        headers: headers,
        body: data
    }).then(handleResponse);
}

function deleteSet(id) {
    return fetch(`${config.apiUrl}/admin/set/${id}`, {
        method: 'DELETE',
        headers: authHeader()
    }).then(handleResponse);
}

function exportSet(id) {
    let headers = authHeader();
    delete headers['Content-Type'];
    delete headers['Accept'];
    headers['Accept'] = 'application/csv';
    headers['Content-Type'] = 'application/csv';
    return fetch(`${config.apiUrl}/admin/set/${id}`, {
        method: 'GET',
        headers: headers,
        responseType: 'arraybuffer',
    }).then((response) => {
        let filename = '';
        let disposition = response.headers.get('Content-Disposition');
        if (disposition && disposition.indexOf('attachment') !== -1) {
            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            let matches = filenameRegex.exec(disposition);
            if (matches != null && matches[1]) {
                filename = matches[1].replace(/['"]/g, '');
            }
            return response.blob().then(blob => {
                    if (window.navigator.msSaveOrOpenBlob) {
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        let elem = window.document.createElement('a');
                        elem.href = window.URL.createObjectURL(blob);
                        elem.download = filename;
                        document.body.appendChild(elem);
                        elem.click();
                        document.body.removeChild(elem);
                    }
                }
            );
        }
    });
}

function getSet(id) {
    return fetch(`${config.apiUrl}/admin/lesson/${id}/edit`, {
        method: 'GET',
        headers: authHeader()
    }).then(handleResponse).then(data => {
        return data.data;
    });
}

function updateSet(data) {
    return fetch(`${config.apiUrl}/admin/set/${data.id}`, {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(data)
    }).then(handleResponse);
}

