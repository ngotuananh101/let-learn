<template>
    <div class="container pt-3">
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-radius-xl justify-content-between">
            <a class="navbar-brand" href="#">Back</a>
            <div class="justify-content-center" id="navbarNav">
                <ul class="nav nav-pills bg-transparent border-0" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true" style="color: black; font-weight: bold">News
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false" style="color: black; font-weight: bold">Excercite
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact"
                                aria-selected="false" style="color: black; font-weight: bold">Members
                        </button>
                    </li>

                </ul>
            </div>
            <div class="d-flex align-items-center">
                <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                     style="height: 30px" class="rounded-circle">
                <span class="mr-2">Long Cute pho mai que</span>
            </div>
        </nav>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="card my-4">
                    <div class="card-header border-radius-xl bg-info"
                         style="color: black; font-weight: bold; font-size: 18px">
                        IS1431
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="onSubmit">
                            <div class="form-group">
                                <label for="message-input">Announce something to the class:</label>
                                <textarea class="form-control" id="message-input" name="message"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file-input">Attach a file:</label>
                                <input type="file" class="form-control-file" id="file-input" name="file">
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute pho mai que</span>
                        </div>
                        <div class="pt-2">
                            <p>Chúc các lớp nghỉ hè vui vẻ
                                ....và chuẩn bị tinh thần ôn thi tốt</p>
                        </div>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="comment-input">Write a comment:</label>
                                <textarea class="form-control" id="comment-input" rows="1"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    </div>
                </div>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute pho mai que</span>
                        </div>
                        <div class="pt-2">
                            <p>Tài liệu ôn thi</p>
                            <p>https://quizlet.com/466530368/acc101-flash-cards/</p>
                        </div>
                        <hr>
                        <form>
                            <div class="form-group">
                                <label for="comment-input">Write a comment:</label>
                                <textarea class="form-control" id="comment-input" rows="1"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="card my-4  mx-md-12" v-for="(lesson, index) in lessons" :key="index">
                    <div class="row">
                        <div class="col">
                            <div
                                class="card-body"
                                :data-toggle="'#lesson-detail-' + index"
                                @click="toggleLessonDetails(index)"
                                style="color: black; font-weight: bold"
                            >
                                {{ lesson.title }}
                            </div>
                            <div
                                :id="'lesson-detail-' + index"
                                class="collapse"
                                :class="{ show: lesson.showDetails }"
                            >
                                <hr/>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-8">
                                            <p>{{ lesson.duration }}</p>
                                            <p>{{ lesson.numQuestions }} questions</p>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary">Start</button>
                                        </div>
                                        <div class="col-2">
                                            <button class="btn btn-primary" @click="viewExerciseResult(lesson.id)">Result</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <div class="container">
                    <div class="fixed-plugin">
                        <a class="fixed-plugin-button  position-fixed p-3">Add</a>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="pt-3">Teacher</h4>
                            <div class="card my-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute pho mai que</span>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <h4 class="pt-3">Students</h4>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-1" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-1">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-2" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-2">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card my-4">
                                <div class="card-body d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <img src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG" style="height: 30px" class="rounded-circle">
                                        <span class="ms-2 me-auto">Long Cute</span>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="student-options-3" data-bs-toggle="dropdown" aria-expanded="false">...</button>
                                        <ul class="dropdown-menu" aria-labelledby="student-options-3">
                                            <li><a class="dropdown-item" href="#">Delete</a></li>
                                            <li><a class="dropdown-item" href="#">Hide</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            lessons: [
                {
                    title: 'Java - Lesson 1',
                    duration: '30 min',
                    numQuestions: '25',
                    showDetails: false,
                },
                {
                    title: 'Java - Lesson 2',
                    duration: '40 min',
                    numQuestions: '25',
                    showDetails: false,
                },
                {
                    title: 'Java - Lesson 3',
                    duration: '30 min',
                    numQuestions: '25',
                    showDetails: false,
                },
            ],
        };
    },
    mounted() {
        // Initialize CKEditor on the message input field
        ClassicEditor.create(document.querySelector('#message-input'))
            .catch(error => {
                console.error(error);
            });
    },
    methods: {
        onSubmit() {
            // Handle form submission here
        },
        toggleLessonDetails(index) {
            this.lessons.forEach((lesson, i) => {
                if (i === index) {
                    lesson.showDetails = !lesson.showDetails;
                } else {
                    lesson.showDetails = false;
                }
            });
        },
        viewExerciseResult(exerciseId) {
            const url = '/class/detail/' + exerciseId;
            window.location.href = url;
        },
    }
}
</script>
