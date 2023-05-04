import handleResponse from "../../other/handle-response";
import authHeader from "../../other/auth-header";

export const learnService = {
    loadFlashCard,
    loadLearn,
    loadTest,
    updateResult,
    sendTestResult,
    loadSelfTest,
    addTest,
    importExcelFile,
    updateComment,
    loadNew,
    relearn,
};

function loadFlashCard(lesson_id, roleName, userId) {
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };
    console.log(lesson_id);
    return fetch(`/api/${roleName}/main/${userId}?type=detail&lesson_id=${lesson_id}`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            console.log(data.data.lessonDetail);
            return data.data.lessonDetail;
        });
}
function loadLearn(data) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify({
            lesson_id: data.id,
            quantity: 8,
            reverse: 0,
            mix_details: 0,
            mix_answers: 0,
        }),
    };
    return fetch(`/api/${data.roleName}/main?type=test`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data.lesson_details;
        });
}
function loadTest(id) {
    const requestOptions = {
        method: "GET",
        headers: authHeader(),
    };
    return fetch(`/api/student/quiz/${id}?type=question`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data.data;
        });
}
function loadSelfTest(id, roleName) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify({
            lesson_id: id,
            quantity: 20,
            reverse: 0,
            mix_details: 0,
            mix_answers: 0,
        }),
    };
    return fetch(`/api/${roleName}/main?type=test`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data.lesson_details;
        });
}
function loadNew(id) {
    const requestOptions = {
        method: 'GET',
        headers: authHeader(),
        // body: JSON.stringify({class_id: id}),
    };
    return fetch(`/api/forum/post/?class_id=${id}`, requestOptions)
        .then(handleResponse)
        .then(data => {
            return data.posts;
        });
}
function sendTestResult(data) {
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    return fetch(
        `/api/student/quiz?type=answer&quiz_id=${data.id}`,
        requestOptions
    ).then(handleResponse);
}
function updateResult(data) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify({
            lesson_id: data.lesson_id,
            learned: data.learned,
            relearn: data.relearn,
        }),
    };
    return fetch(
        `/api/${data.roleName}/main/${data.user_id}/?type=learned`,
        requestOptions
    )
        .then(handleResponse)
        .then((data) => {
            console.log(data);
            return data;
        });
}

function addTest(test) {
    console.log(test);
    const requestOptions = {
        method: "POST",
        headers: authHeader(),
        body: JSON.stringify(test),
    };

    return fetch(`/api/${test.roleName}/quiz?type=quiz`, requestOptions)
        .then(handleResponse)
        .then((test) => {
            return test;
        });
}
function relearn(data) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify(data),
    };
    const userData = JSON.parse(localStorage.getItem("userData"));
    return fetch(`/api/${userData.info.role.name}/main/${data.user_id}/?type=done&choice=relearnall`, requestOptions)
        .then(handleResponse)
        .then((data) => {
            return data;
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

function updateComment(data) {
    const requestOptions = {
        method: "PUT",
        headers: authHeader(),
        body: JSON.stringify({
            comment_id: data.comment_id,
            comment: data.comment,
            status: data.status,
            votetype: data.votetype,
        }),
    };
    return fetch(`/api/forum/post/1?type=comment`, requestOptions).then(
        handleResponse
    );
}
