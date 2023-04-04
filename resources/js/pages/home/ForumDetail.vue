<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header">
                        <h2>{{ question }}</h2>

                    </div>
                    <div class="card-body">
                        <p>{{ description }}</p>
                    </div>
                    <p class="text-xl-end p-lg-2">
              <span class="bold">
                Created on: {{ questionDate }} | Tags:
                <span v-for="(tag, index) in tags" :key="index">{{ tag }}</span>
              </span>
                    </p>
                </div>

                <div class="card mb-3" v-for="(answer, index) in answers" :key="index">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <div class="text-center">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <i
                                                class="fa-solid fa-caret-up text-success"
                                                @click="upvoteAnswer(index)"
                                            ></i>
                                            <div class="col-md- justify-content-end align-items-center">
                                                {{ answer.count }}
                                            </div>
                                            <i
                                                class="fa-solid fa-caret-down text-danger mt-2"
                                                @click="downvoteAnswer(index)"
                                            ></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9 m-lg-2 d-flex align-items-center">
                                <div v-html="answer.text"></div>
                            </div>
                            <div class="col-md-1">
                                <div class="text-center">
                                    <img
                                        :src="answer.avatar"
                                        class="rounded-circle d-flex align-items-center"
                                        alt="avatar"
                                        style="width: 50px;"
                                    />
                                    <div>{{ answer.username }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <form @submit.prevent="submitAnswer">
                            <div class="form-group">
                                <label for="answerInput">Your Answer</label>
                                <textarea
                                    class="form-control"
                                    id="answerInput"
                                    v-model="newAnswer"
                                ></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
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
            question: "What is your favorite programming language?",
            questionDate: "April 4, 2023",
            tags: ["programming"],
            description:
                "Please share your thoughts on your preferred programming language.",
            answers: [
                {
                    count: 10,
                    text: "<p>My favorite programming language is JavaScript.</p>",
                    avatar: "https://i.pravatar.cc/150?img=3",
                    username: "John Doe",
                },
                {
                    count: 5,
                    text: "<p>I prefer Python over JavaScript.</p>",
                    avatar: "https://i.pravatar.cc/150?img=2",
                    username: "Jane Doe",
                },
            ],
            newAnswer: "",
            newAnswerUsername: "Hai Long",
            editor: ClassicEditor,
        };
    },
    // mounted() {
    //     ClassicEditor
    //         .create(document.querySelector('#answerInput'))
    //         .then(editor => {
    //             this.editor = editor
    //         })
    //         .catch(error => {
    //             console.error(error)
    //         })
    // },

    methods: {
        upvoteAnswer(index) {
            this.answers[index].count++;
        },
        downvoteAnswer(index) {
            this.answers[index].count--;
        },
        submitAnswer() {
            try {
                if (this.newAnswer === "") {
                    throw new Error("Answer cannot be empty.");
                }

                this.answers.push({
                    count: 0,
                    text: this.newAnswer,
                    avatar: "https://i.pravatar.cc/150?img=7",
                    username: this.newAnswerUsername,
                });

                this.newAnswer = "";
            } catch (e) {
                alert(e.message);
            }
        },
    },
};
</script>
