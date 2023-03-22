<template>
    <div class="container pt-3">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-radius-xl">
            <a class="navbar-brand" href="#">Back</a>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
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
                    <div class="card-header border-radius-xl bg-info" style="color: black; font-weight: bold; font-size: 18px">
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
                <div class="card my-4">
                    <div
                        class="card-body"
                        data-toggle="collapse"
                        :href="'#lesson1-detail'"
                        @click="showLesson1Details = !showLesson1Details"
                        :aria-expanded="showLesson1Details"
                        aria-controls="lesson1-detail"
                        style="color: black; font-weight: bold"
                    >
                        Java - Lesson 1
                    </div>
                    <div
                        id="lesson1-detail"
                        class="collapse"
                        :class="{ show: showLesson1Details }"
                    >
                        <hr/>
                        <div class="card-body">
                            Viết chương trình Java in ra dòng chữ "Hello world!" trên màn hình
                            console.
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="lesson1-input">Submit:</label>
                            <input
                                type="file"
                                class="form-control-file"
                                id="lesson1-input"
                                name="file"
                            />
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="card my-4">
                    <div
                        class="card-body"
                        data-toggle="collapse"
                        :href="'#lesson2-detail'"
                        @click="showLesson2Details = !showLesson2Details"
                        :aria-expanded="showLesson2Details"
                        aria-controls="lesson2-detail"
                        style="color: black; font-weight: bold"
                    >
                        Java - Lesson 2
                    </div>
                    <div
                        id="lesson2-detail"
                        class="collapse"
                        :class="{ show: showLesson2Details }"
                    >
                        <hr/>
                        <div class="card-body">
                            Viết chương trình Java tính tổng của hai số nguyên đầu vào và in ra
                            kết quả.
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label for="lesson2-input">Submit:</label>
                            <input
                                type="file"
                                class="form-control-file"
                                id="lesson2-input"
                                name="file"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                <h4 class="pt-3">Teacher</h4>
                <div class="card my-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute pho mai que</span>
                        </div>
                    </div>
                </div>
                </div>
                <hr/>
                <h4 class="pt-3">Students</h4>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute</span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute</span>
                        </div>
                    </div>
                </div><hr/>
                <div class="card my-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img
                                src="https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG"
                                style="height: 30px" class="rounded-circle">
                            <span class="mr-2">Long Cute</span>
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
            showLesson1Details: false,
            showLesson2Details: false,
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
    }
}
</script>
