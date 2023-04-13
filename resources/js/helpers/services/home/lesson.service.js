import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";
import readXlsxFile from "read-excel-file";

export const lessonService = {
    importExcelFile,
    addLesson,
    // updateLesson
};

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
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(lesson),
    };

    return fetch(`/api/user/lesson`, requestOptions)
        .then(handleResponse)
        .then((lesson) => {
            return lesson;
        });
}

// function updateLesson(lesson) {
//     const requestOptions = {
//         method: 'PUT',
//         headers: authHeader(),
//         body: JSON.stringify(lesson)
//     };

//     return fetch(`/api/admin/lesson/${lesson.id}`, requestOptions)
//         .then(handleResponse)
//         .then(lesson => {
//             return lesson;
//         });
// }
