<template>
    <div class="row mt-3">
        <div class="col-md-7 col-12">
            <div class="container">
                <div class="card" id="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-subtitle text-muted">{{ cardsCount.currentCardIndex + 1 }} /
                                    {{ cardsCount.totalCards }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="height: 50vh; max-height: 70vh;" @click="rotateCard">
                        <div class="h-100 d-flex justify-content-center align-items-center">
                            <p class="card-text fs-4" id="text"></p>
                        </div>
                    </div>
                    <h5 class="card-footer"></h5>
                </div>
                <div class="mt-3 mx-1 row justify-content-between">
                    <button class="btn btn-outline-warning col-sm-3 col-5" @click="previousCard">Previous</button>
                    <button class="btn btn-outline-success col-sm-3 col-5" @click="nextCard">Next</button>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-12">
            <div class="row">
                <!-- <h2>{{ data.lesson.name }}</h2> -->
                <div class="col-12 mb-3 row align-items-center justify-content-center">
                    <div class="col-2">
                        <img src="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                            class="rounded-circle" alt="Avatar" style="width: 50px" />
                    </div>
                    <div class="col-8">
                        <p class="m-0">
                            {{ this.user.name }}
                        </p>
                    </div>
                    <div class="col-2">
                        <div class="dropdown show">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false"></button>
                            <ul class="dropdown-menu">
                                <router-link :to="{ name: 'lesson.edit', params: { id: id } }">
                                    <li><a class="dropdown-item" href="#">Edit</a></li>
                                </router-link>
                                <li><a class="dropdown-item" @click="deleteLesson">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <router-link :to="{ name: 'home.flashcard', params: { id: id } }">
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
                        </router-link>
                    </div>
                    <div class="col-md-6 col-12">
                        <router-link :to="{ name: 'home.learn', params: { id: id } }">
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
                        </router-link>
                    </div>
                    <div class="col-md-6 col-12 pt-3">
                        <router-link :to="{ name: 'home.selftest', params: { id: id } }">
                            <div class="card">
                                <!-- :href="{/lesson/selftest/${id}}" -->
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
                        </router-link>
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
    <div class="mt-4">
        <!--show the lesson's description by lesson id -->
        <h5 class="mt-4">Description</h5>
        <!-- <p>{{ data.lesson.description }}</p> -->
    </div>
    <h6>Relearn:</h6>
<!--     <h6>Relearn: {{ data.relearn.length }}</h6>-->
    <div class="col-12" v-for="relearn in relearns">
        <div class="card mt-4">
            <div class="card-body">
                <p class="card-text text-success">{{ relearn.term }}</p>
                <p class="card-text">{{ relearn.definition }}</p>
            </div>
        </div>
    </div>
    <h6>NotLearn:</h6>
<!--     <h6>NotLearn: {{ data.notLearn.length }}</h6>-->
    <div class="col-12" v-for="notLearn in notLearns">
        <div class="card mt-4">
            <div class="card-body">
                <p class="card-text text-success">{{ notLearn.term }}</p>
                <p class="card-text">{{ notLearn.definition }}</p>
            </div>
        </div>
    </div>
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
            user: null,
            data: null,
            currentCardIndex: 0,
            currentSide: "front",
            relearns: null,
            notLearns: null,
            type: null,
        };
    },
    title() {
        return (
            "Home - " + document.getElementsByTagName("meta")["title"].content
        );
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "lesson/request") {
            } else if (mutation.type === "lesson/success") {
                if (!this.type) {
                    this.data = mutation.payload;
                    console.log(this.data);
                    this.relearns = this.data.relearn = mutation.payload.relearn;
                    this.notLearns = this.data.notLearn = mutation.payload.notLearn;
                    document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
                }
                if (this.type === 'delete') {
                    this.$root.showSnackbar('Delete lesson successfully', 'success');
                    this.$router.push({ name: 'home.home' });
                }
            } else if (mutation.type === "lesson/failure") {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch("lesson/getFlashCard", { id: this.id, roleName: this.user.role.name });
    },
    methods: {
        updateTitle(title) {
            this.$emit("change-title", title);
        },
        nextCard() {
            if (this.currentCardIndex < this.data.notLearn.length - 1) {
                this.currentCardIndex++;
            } else {
                this.currentCardIndex = 0;
            }
            document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
            } else {
                this.currentCardIndex = this.data.notLearn.length - 1;
            }
            document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
        },
        rotateCard(e) {
            let card = document.getElementById("card");
            if (this.currentSide === "front") {
                card.classList.add("rotate");
                document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].term;
                this.currentSide = "back";
            } else {
                card.classList.remove("rotate");
                document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
                this.currentSide = "front";
            }
        },
        deleteLesson() {
            if (confirm('Are you sure?')) {
                this.type = 'delete';
                this.$store.dispatch('lesson/deleteLesson', { id: this.id, roleName: this.user.role.name });
            }
            this.$router.push({ name: 'home.home' });
        },
    },
    computed: {
        cardsCount() { // add cardsCount computed property
            return {
                currentCardIndex: this.currentCardIndex,
                totalCards: this.data ? this.data.notLearn.length : 0
            };
        }
    }
}
</script>

<style scoped>
.card {
    transition: all 0.3s;
    transform-style: preserve-3d;
}

.rotate {
    transform: rotateY(360deg);
}

.card-text {
    white-space: pre-line;
}
</style>
