<template>
    <div class="row mt-3">
        <div class="col-md-7 col-12">
            <div class="flashcards-container">
                <section class="card-container">
                    <div class="card d-flex justify-content-center align-items-center p-md-5 p-3"
                        style="min-height: 50vh; max-height: 50vh;" @click="rotateCard">
                        <p id="card-title" class="fs-6" style="white-space: pre-line;"></p>
                    </div>
                </section>
                <!-- add card count display -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button @click="previousCard" class="btn">Previous</button>
                    <div class="card-count w-100 text-center">{{ cardsCount.currentCardIndex + 1 }} / {{
                        cardsCount.totalCards }}</div>
                    <button @click="nextCard" class="btn">Next</button>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-12">
            <div class="row">
                <!-- <h2>{{ lesson ? lesson.name : 'loading' }}</h2> -->
                <div class="col-12 mb-3 row align-items-center justify-content-center">
                    <div class="col-2">
                        <img src="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                            class="rounded-circle" alt="Avatar" style="width: 150px" size="md" circular />
                    </div>
                    <div class="col-6">
                        <p class="m-0">
                            <!--get user name-->
                            <!-- {{ lesson ? lesson.user.name : 'loading' }}
                            {{ this.user.email_verified_at
                                ? "verified"
                                : "unverified" }} -->
                        </p>
                    </div>
                    <div class="col-4 p-0">
                        <div class="dropdown float-end">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Flashcard</h5>
                                    <p class="card-text text-sm">
                                        Review terms and definitions
                                    </p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/5484/5484383.png" class="img-fluid"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Learn</h5>
                                    <p class="card-text text-sm">
                                        Study with MC, T/F, and other questions
                                    </p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/4207/4207247.png" class="img-fluid"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 pt-3">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Test</h5>
                                    <p class="card-text text-sm">
                                        Create a test for this lesson
                                    </p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3068/3068553.png" class="img-fluid"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12 pt-3">
                        <div class="card">
                            <div class="card-body p-3 row">
                                <div class="col-8">
                                    <h5 class="card-title mb-0">Report</h5>
                                    <p class="card-text text-sm">
                                        Report lesson problem to admin
                                    </p>
                                </div>
                                <div class="col-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/10209/10209878.png" class="img-fluid"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="lesson" class="mt-4">
        <!--show the lesson's description by lesson id -->
        <h5 class="mt-4">Description</h5>
        <p>{{ lesson.description }}</p>
    </div>
    <!-- <h5 class.js="mt-4">Number of terms in this lesson: {{ cardsCount.totalCards }}</h5> -->
    <!-- <div class="mt-4" v-if="relearns">
        <h6>Relearn: {{ relearns.length }}</h6>
        <div class="row mt-4">
            <div class="col-12" v-for="relearn in relearns">
                <LessonCard :title="relearn.term" :value="relearn.definition" />
            </div>
        </div>
    </div> -->
    <!-- <div class="card w-75">
        <div class="card-body" v-for="relearn in relearns">
            <h5 class="card-title">{{ relearn.term }}</h5>
            <p class="card-text">{{ relearn.definition }}</p>
        </div>
    </div> -->
    <div class="card mb-3" v-if="relearns">
        <div class="card-body" v-for="relearn in relearns" :key="lesson_id">
            <h5 class="card-title">{{ relearn.term }}</h5>
            <p class="card-text">{{ relearn.definition }}</p>
        </div>
    </div>
    <!-- <div class="mt-4" v-if="notlearns">
        <h6>Not learn: {{ notlearns.length }}</h6>
        <h6>Not learn: 100</h6>
        <div class="row mt-4">
            <div class="col-12" v-for="notlearn in notlearns">
                <LessonCard :title="notlearn.term" :value="notlearn.definition" />
            </div>
        </div>
    </div> -->
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "lesson",
    props: ["lesson_id"],
    components: {
    },
    data() {
        return {
            id: this.$route.params.id,
            user: JSON.parse(localStorage.getItem("user")),
            lesson: null,
            relearns: null,
            notlearns: null,
        };
    },
    title() {
        return (
            "Home - " + document.getElementsByTagName("meta")["title"].content
        );
    },
    mounted() {
        console.log(this.user);
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "lesson/request") {
            } else if (mutation.type === "lesson/requestSuccess") {
                this.lesson = mutation.payload.lesson;
                this.relearns = mutation.payload.relearn;
                this.notlearns = mutation.payload.notlearn;
            } else if (mutation.type === "lesson/requestFailure") {
            }
        });
        this.$store.dispatch("lesson", this.id);
    },
    methods: {
        // ...mapActions("lesson", ["getLesson"]),
        // ...mapActions("card", ["getCards"]),
        nextCard() {
            console.log(this.currentCardIndex);
            if (this.currentCardIndex < this.data.length - 1) {
                this.currentCardIndex++;
            } else {
                this.currentCardIndex = 0;
            }
            document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
                this.$emit("change-progress", Math.round((this.currentCardIndex / this.data.length) * 100));
            } else {
                this.currentCardIndex = this.data.length - 1;
            }
            document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
        },
        rotateCard(e) {
            let card = document.getElementById("card");
            if (this.currentSide === "front") {
                card.classList.add("rotate");
                document.getElementById("text").innerHTML = this.data[this.currentCardIndex].term;
                this.currentSide = "back";
            } else {
                card.classList.remove("rotate");
                document.getElementById("text").innerHTML = this.data[this.currentCardIndex].definition;
                this.currentSide = "front";
            }
        }
    },
    computed: {
        cardsCount() { // add cardsCount computed property
            return {
                currentCardIndex: this.currentCardIndex,
                totalCards: this.data ? this.data.length : 0
            };
        }
    }
}
</script>

<style scoped>
.card {
    transition: all 0.3s;
    transform-style: preserve-3d;
    flex: 1;
}

.rotate {
    transform: rotateY(360deg);
}
</style>
