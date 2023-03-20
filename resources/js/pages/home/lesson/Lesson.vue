<template>
    <div>
        <div v-if="lesson">
            <h2>{{ lesson.name }}</h2>
        </div>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-8">
                <div class="flashcards-container">
                    <section class="card-container">
                        <div class="card d-flex justify-content-center align-items-center p-5" @click="rotateCard">
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
                    <!-- add settings menu popup -->
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-4">
                <div class="col-lg-4 col-md-4 col-4">
                    <div class="row">
                        <div class="col">
                            <argon-avatar
                                img="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                                alt="Avatar" size="md" circular />
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-8">
                    <h6>Composed by user</h6>
                    <div v-if="lesson">
                        <h7>{{ lesson.description }}</h7>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <h5 class="mt-4">Number of terms in this lesson: {{ cardsCount.totalCards }}</h5> -->
    <div v-if="relearns">
        <h6>Relearn: {{ relearns.length }}</h6>
        <div class="row">
            <div class="col-12" v-for="relearn in relearns">
                <LessonCard :title="relearn.term" :value="relearn.definition" />
            </div>
        </div>
    </div>
    <div v-if="notlearns">
        <h6>Relearn: {{ notlearns.length }}</h6>
        <div class="row">
            <div class="col-12" v-for="notlearn in notlearns">
                <LessonCard :title="notlearn.term" :value="notlearn.definition" />
            </div>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";
import { mapActions, mapGetters } from "vuex";

export default {
    name: "lesson",
    components: {
        LessonCard,
        ArgonButton,
        ArgonAvatar,
    },
    data() {
        return {
            id: this.$route.params.id,
            data: [],
            currentCardIndex: 0,
            currentSide: 'front',
            showSettings: false, // add showSettings data property
            relearns: null,
            notlearns: null,
            lesson_id: this.$route.params.id,
            lesson: null,
        };
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.$store.dispatch('home/getLessonDetailRelearn', this.lesson_id).then(
            relearn => {
                this.relearns = relearn;
            }
        );
        this.$store.dispatch('home/getLessonDetailNotLearn', this.lesson_id).then(
            notlearn => {
                this.notlearns = notlearn;
            }
        );
        this.$store.dispatch('home/getLessonInfo', this.lesson_id).then(
            lesson => {
                this.lesson = lesson;
            }
        );

    },
    mounted() {
        // get id from params
        this.getLessons(this.id).then(detail => {
            this.data = detail;
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        });
    },
    methods: {
        ...mapActions({
            getLessons: 'learn/getLessons'
        }),
        nextCard() {
            if (this.currentCardIndex < this.data.length - 1) {
                this.currentCardIndex++;
            } else {
                this.currentCardIndex = 0;
            }
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
            } else {
                this.currentCardIndex = this.data.length - 1;
            }
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        },
        toggleSettings() { // add toggleSettings method
            this.showSettings = !this.showSettings;
        },
        // text to speech
        speak(text) {
            const msg = new SpeechSynthesisUtterance();
            msg.text = text;
            window.speechSynthesis.speak(msg);
        },
        rotateCard(e) {
            let card = e.target.closest('.card');
            if (this.currentSide === 'front') {
                card.classList.add('rotate');
                document.getElementById('card-title').classList.add('card-title-transition');
                document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].term;
                this.currentSide = 'back';
            } else {
                card.classList.remove('rotate');
                document.getElementById('card-title').classList.remove('card-title-transition');
                document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
                this.currentSide = 'front';
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
};
</script>
