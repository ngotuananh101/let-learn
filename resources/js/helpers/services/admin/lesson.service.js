import handleResponse from '../../other/handle-response';
import authHeader from '../../other/auth-header';
import readXlsxFile from 'read-excel-file'

export const lessonService = {
    getLessons,
    importExcelFile,
    add,
    getLessonById,
    updateLesson
}

function getLessons() {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/lesson`, requestOptions)
        .then(handleResponse)
        .then(lessons => {
            return lessons;
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

function add(lesson) {
    const requestOptions = {
        method: 'POST',
        headers: authHeader(),
        body: JSON.stringify(lesson)
    };

    return fetch(`/api/admin/lesson`, requestOptions)
        .then(handleResponse)
        .then(lesson => {
            return lesson;
        });
}

function getLessonById(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader()
    };

    return fetch(`/api/admin/lesson/${id}`, requestOptions)
        .then(handleResponse)
        .then(lesson => {
            return lesson;
        });
}

function updateLesson(lesson) {
    const requestOptions = {
        method: 'PUT',
        headers: authHeader(),
        body: JSON.stringify(lesson)
    };

    return fetch(`/api/admin/lesson/${lesson.id}`, requestOptions)
        .then(handleResponse)
        .then(lesson => {
            return lesson;
        });
}
