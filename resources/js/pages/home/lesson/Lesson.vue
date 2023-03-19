<template>
    <header>
        <h2></h2>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-8">
                <div class="flashcards-container">
                    <section class="card-container">
                        <div class="card d-flex justify-content-center align-items-center p-5" @click="rotateCard">
                            <p id="card-title" class="fs-6" style="white-space: pre-line;"></p>
                        </div>
                    </section>
                    <!-- add card count display -->
                    <div class="card-count">{{ cardsCount.currentCardIndex + 1 }} / {{ cardsCount.totalCards }}</div>
                    <div class="buttons-container">
                        <button @click="previousCard">Previous</button>
                        <button @click="nextCard">Next</button>
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
                    <h7>This is a lesson to pass HCM201</h7>
                </div>
            </div>
        </div>
    </header>
    <br>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Flash Card</argon-button></div>
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Learn</argon-button></div>
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Test</argon-button></div>
    </div>
    <br>
    <br>
    <h5>Number of terms in this lesson: 100</h5>

    <br>
    <h6>Relearn: 40</h6>
    <h7>You have already started learning these terms. Continue to promote offline!</h7>
    <div class="row">
        <div class="col-md-6 col-12" v-for="relearn in relearns">
            <LessonCard title="" value="" :description="relearn.description" />
        </div>
    </div>

    <br>
    <h6>Not yet learn: 30</h6>
    <h7>You haven't learned these terms yet! Let's start learning now!</h7>

    <!-- <br>
    <h6>Other lessons from this user</h6> -->
    <!-- <div class="row">
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username"/>
        </div>
    </div> -->
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
            lessons: null,
            id: this.$route.params.id,
            data: [],
            currentCardIndex: 0,
            currentSide: 'front',
            showSettings: false // add showSettings data property
        };
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        // this.$store.dispatch('home/getLesson').then(
        //     lesson => {
        //         console.log(lesson);
        //         this.lessons = lesson;
        //     }
        // );
        this.$store.dispatch('home/getLessonDetail').then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
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

<style scoped>
.rotate {
    transform: rotateY(180deg);
}

.card-title-transition {
    transform: rotateY(180deg);
}

.card-count {
    position: center;
    top: 10px;
    left: 10px;
    font-size: 20px;
}

.settings-button {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 999;
    /* make sure it appears above other elements */
    left: auto;
    /* remove left property */
}

/* add styles for settings menu */
.settings-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 998;
    /* make sure it appears below the settings button */
}

.settings-menu {
    position: relative;
    width: 800px;
    max-width: 90%;
    background-color: #f5f5f5;
    border-radius: 4px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
    padding: 20px;
    animation-name: slide-in-down;
    animation-duration: 0.3s;
    animation-timing-function: ease-out;
}

.settings-close-button {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 5px;
    font-size: 5px;
    line-height: 1;
    color: #666;
    border: none;
    background-color: transparent;
    cursor: pointer;
}

.settings-close-button:hover {
    color: #000;
}

@keyframes slide-in-down {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.settings-close-button:hover {
    color: #000;
}

.flashcards-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 85%;
    height: 40vh;
}

.card-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: calc(100% - 120px);
    /* subtract height of button and header containers */
    border-radius: 4px;
    perspective: 900px;
    background-image: none;
    box-sizing: border-box;
    padding: 20px;
    padding-top: 50px;
    /* add top padding */
    padding-bottom: 50px;
    /* add bottom padding */
}

.card {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: all 0.7s;
    transform-style: preserve-3d;
    background: transparent;
}

.buttons-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    height: 50px;
    margin-top: 20px;
}

button {
    padding: 10px;
    border-radius: 4px;
    background-color: #f5f5f5;
    border: none;
    cursor: pointer;
}

.buttons {
    position: absolute;
    bottom: 10px;
    /* set bottom offset */
    right: 10px;
    /* set right offset */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    /* make sure it appears above other elements */
}

#announcement-button {
    margin-bottom: 400px;
    margin-left: 10px;
    padding: 10px;
    border-radius: 4px;
    background-color: #f5f5f5;
    border: none;
    cursor: pointer;
    position: absolute;
    /* add position: absolute */
    bottom: 10px;
    /* set bottom offset */
    right: 90px;
    /* adjust right offset as needed */
}

.fa-solid.fa-bullhorn {
    font-size: 18px;
}
</style>
