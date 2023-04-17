import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';
import readXlsxFile from "read-excel-file";

export const lessonService = {
    index,
    getLessonById,
    importExcelFile,
    updateLesson,
    add,
    deleteLesson
}

function index(slug) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/lessons/${slug}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function getLessonById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };
    return fetch(`/api/school/lessons/${id}/edit`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}

function importExcelFile(formData) {
    return new Promise((resolve, reject) => {
        let file = formData.get('file');
        let details = [];
        readXlsxFile(file).then((rows) => {
            rows.forEach((row, index) => {
                if (index > 1) {
                    details.push({
                        id: 0,
                        term: row[1],
                        definition: row[2],
                    })
                }
            })
        }).then(() => {
            resolve(details);
        }).catch((error) => {
            reject(error);
        });
    });
}

function updateLesson(lesson) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(lesson)
    };

    return fetch(`/api/school/lessons/${lesson.id}`, requestOptions)
        .then(handleResponse)
        .then(lesson => {
            return lesson;
        });
}

function add(lesson) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(lesson)
    };

    return fetch(`/api/school/lessons`, requestOptions)
        .then(handleResponse)
        .then(lesson => {
            return lesson;
        });
}

function deleteLesson(id) {
    const requestOptions = {
        method: 'DELETE',
        headers: authHeader()
    };

    return fetch(`/api/school/lessons/${id}`, requestOptions)
        .then(handleResponse)
        .then(response => {
            return response;
        });
}
