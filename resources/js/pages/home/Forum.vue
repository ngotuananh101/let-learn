<template>
    <div class="card">
    <div class="container p-5">
        <h1>Forum F17 from VOZ</h1>
        <form @submit.prevent="askQuestion" class="mb-3">
            <div class="input-group">
                <input v-model="newQuestion" placeholder="Ask a question..." class="form-control">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <div v-for="(question, index) in questions" :key="index" class="card my-3">
            <div class="card-body">
                <h2 class="card-title">{{ question.title }}</h2>
                <p class="card-text">{{ question.body }}</p>
                <button @click="toggleAnswer(index)" class="btn btn-secondary mr-2">Answer</button>
                <div v-if="question.showAnswer" class="mt-3">
                    <form @submit.prevent="answerQuestion(index)" class="mb-3">
                        <div class="input-group">
                            <input v-model="newAnswer" placeholder="Enter your answer..." class="form-control">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>

                    <ul class="list-unstyled mt-3">
                        <li v-for="(answer, answerIndex) in question.answers.sort((a, b) => b.likes - a.likes)" :key="answerIndex" class="card my-3">
                            <div class="card-body row align-items-center">
                                <div class="col-md-1 d-none d-md-block">
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <span>{{ answer.user.charAt(0).toUpperCase() }}</span>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <h5 class="card-title">{{ answer.user }}</h5>
                                    <div v-if="!answer.isEditing">
                                        <p class="card-text">{{ answer.body }}</p>
                                    </div>
                                    <div v-if="answer.isEditing">
                                        <form @submit.prevent="saveAnswer(index, answerIndex)">
                                            <div class="input-group">
                                                <input v-model="answer.newBody" placeholder="Edit your answer..." class="form-control">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                            <span class="text-danger">{{ answer.error }}</span>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="answer-options-{{ index }}-{{ answerIndex }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="answer-options-{{ index }}-{{ answerIndex }}">
                                            <a class="dropdown-item" href="#" @click.prevent="editAnswer(index, answerIndex)">Edit</a>
                                            <a class="dropdown-item" href="#" @click.prevent="confirmDeleteAnswer(index, answerIndex)">Delete</a>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <button @click="rateAnswer(question, answer)" class="btn btn-link"><i class="fa-regular fa-thumbs-up"></i></button>
                                <div>Vote: {{ answer.likes }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import 'bootstrap/dist/css/bootstrap.min.css';

export default {
    data() {
        return {
            newQuestion: '',
            newAnswer: '',
            questions: [
                {
                    title: 'What is Vue.js?',
                    body: 'I am new to Vue.js and want to learn more about it.',
                    showAnswer: false,
                    answers: [
                        {
                            user: 'John Doe',
                            body: 'Vue.js is a progressive JavaScript framework that makes it easy to build dynamic and responsive web applications. It uses a virtual DOM and reactive data binding to provide high performance and flexibility, and has a rich set of built-in features such as component-based architecture, modular development, and simple syntax.',
                            likes: 2,
                            isEditing: false,
                            newBody: '',
                            error: ''
                        },
                        {
                            user: 'Jane Smith',
                            body: 'I agree with John. Vue.js is a great choice for building modern web applications. It has a low learning curve and is easy to integrate with other libraries and tools.',
                            likes: 1,
                            isEditing: false,
                            newBody: '',
                            error: ''
                        }
                    ]
                },
                {
                    title: 'Chưa biết câu trả lời',
                    body: 'Sao bạn Long lại pờ rồ phô mai que thế nhỉ?',
                    showAnswer: false,
                    answers: [
                        {
                            user: 'DatDX',
                            body: 'Chưa thể lý giải luôn á',
                            likes: 3,
                            isEditing: false,
                            newBody: '',
                            error: ''
                        },
                        {
                            user: 'AnhNT',
                            body: '10 điểm cho Long',
                            likes: 3,
                            isEditing: false,
                            newBody: '',
                            error: ''
                        },
                    ]
                },
                {
                    title: 'What are some good resources for learning Vue.js?',
                    body: 'I want to learn more about Vue.js but don\'t know where to start. What are some good tutorials or courses to get started with?',
                    showAnswer: false,
                    answers: [
                        {
                            user: 'Bob Johnson',
                            body: 'There are many great resources available online for learning Vue.js. Some of my favorites include the official documentation, the Vue Mastery video courses, and the "Vue.js 2 - The Complete Guide" course on Udemy.',
                            likes: 3,
                            isEditing: false,
                            newBody: '',
                            error: ''
                        }
                    ]
                },
            ]
        };
    },
    methods: {
        askQuestion() {
            this.questions.push({
                title: this.newQuestion,
                body: '',
                showAnswer: false,
                answers: []
            });
            this.newQuestion = '';
        },
        toggleAnswer(index) {
            this.questions[index].showAnswer = !this.questions[index].showAnswer;
        },
        answerQuestion(index) {
            if (this.newAnswer.trim() !== '') {
                this.questions[index].answers.push({
                    user: 'Anonymous',
                    body: this.newAnswer,
                    likes: 0,
                    isEditing: false,
                    newBody: '',
                    error: ''
                });
                this.newAnswer = '';
                this.toggleAnswer(index);
            }
        },
        rateAnswer(question, answer) {
            answer.likes++;
        },
        editAnswer(questionIndex, answerIndex) {
            const answer = this.questions[questionIndex].answers[answerIndex];
            answer.isEditing = true;
            answer.newBody = answer.body; // set newBody to current body for easier editing
        },
        confirmDeleteAnswer(questionIndex, answerIndex) {
            if (confirm('Are you sure you want to delete this answer?')) {
                this.deleteAnswer(questionIndex, answerIndex);
            }
        },
        deleteAnswer(questionIndex, answerIndex) {
            this.questions[questionIndex].answers.splice(answerIndex, 1);
        },
        saveAnswer(questionIndex, answerIndex) {
            const answer = this.questions[questionIndex].answers[answerIndex];
            if (answer.newBody.trim() === '') {
                answer.error = "Answer cannot be empty";
                return;
            }
            answer.body = answer.newBody;
            answer.isEditing = false;
            answer.error = '';
        }
    }
};
</script>
