<template>
    <div class="container my-5">
        <h1 class="text-center mb-4">Essay Quiz</h1>
        <div v-for="(question, index) in questions" :key="index" class="mb-5">
            <h2>Question {{ index + 1 }}</h2>
            <p>{{ question }}</p>
            <textarea
                v-model="answers[index]"
                class="form-control"
                rows="5"
                placeholder="Enter your answer here"
            ></textarea>
        </div>
        <button @click="submitQuiz" class="btn btn-primary d-block mx-auto mt-4">Submit</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            questions: [
                "What are the benefits of exercise?",
                "Describe a moment in your life when you overcame a challenge.",
                "What are some ways that technology has changed the way we communicate?"
            ],
            answers: []
        };
    },
    methods: {
        submitQuiz() {
            // Submit quiz logic goes here
        }
    }
};
</script>

<style scoped>
h1 {
    font-size: 3rem;
    font-weight: bold;
}

h2 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

</style>
