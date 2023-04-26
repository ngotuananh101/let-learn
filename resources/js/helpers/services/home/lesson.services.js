import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";
import readXlsxFile from "read-excel-file";
import role from "../../stores/modules/admin/role";

export const lessonService = {
    importExcelFile,
    addLesson,
    deleteLesson,
    getLessonById,
    updateLesson,
    loadFlashCard,
};

function loadFlashCard(id, roleName) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify({ lesson_id: id }),
    };
    return fetch(`/api/${roleName}/main?type=detail_split`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data.data;
        });
}

function importExcelFile(formData) {
    return new Promise((resolve, reject) => {
        let file = formData.get("file");
        let details = [];
        readXlsxFile(file)
            .then((rows) => {
                rows.forEach((row, index) => {
                    if (index > 1) {
                        details.push({
                            id: 0,
                            term: row[1],
                            definition: row[2],
                        });
                    }
                });
            })
            .then(() => {
                resolve(details);
            })
            .catch((error) => {
                reject(error);
            });
    });
}

function addLesson(lesson) {
    console.log(lesson);
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(lesson),
    };

    return fetch(`/api/${lesson.roleName}/lesson`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}

function deleteLesson(id, roleName) {
    const requestOptions = {
        method: "DELETE",
        headers: authHeader(),
    };

    return fetch(`/api/${roleName}/lesson/${id}`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}

function getLessonById(id, roleName) {
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };

    return fetch(`/api/${roleName}/lesson/${id}`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}

function updateLesson(lesson) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify(lesson),
    };
    console.log(lesson);
    return fetch(`/api/${lesson.roleName}/lesson/${lesson.id}`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}
