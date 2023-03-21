<template>
    <div class="flashcards-container">
        <section class="card-container">
            <div class="card-count text-center w-100">{{ cardsCount.currentCardIndex + 1 }} / {{ cardsCount.totalCards }}</div>
            <div class="card d-flex justify-content-center align-items-center p-5" @click="rotateCard">
                <p id="card-title" class="fs-3" style="white-space: pre-line;"></p>
            </div>
        </section>
        <div class="buttons-container ">
            <button class="w-15" @click="previousCard">Previous</button>
            <button class="w-15" @click="nextCard">Next</button>
        </div>
        <div class="buttons">
            <div>
                <button id="announcement-button" class="bg-transparent" @click="speak(this.data[currentCardIndex].definition)"><i class="fa-regular fa-volume fs-2"></i></button>
            </div>
        </div>
    </div>

</template>


<script>
import { mapActions, mapGetters } from "vuex";
export default {
    name: "Flashcards",
    data() {
        return {
            id : this.$route.params.id,
            data: [],
            currentCardIndex: 0,
            currentSide: 'front',
            showSettings: false // add showSettings data property
        }
    },
    mounted() {
        // get id from params
        this.getLessons(this.id).then(data => {
            this.data = data.detail;
            this.updateTitle(data.lesson.name);
            document.getElementById('card-title').innerHTML = this.data[this.currentCardIndex].definition;
        });
    },
    methods: {
        ...mapActions({
            getLessons: 'learn/getLessons'
        }),
        updateTitle(title) {
            this.$emit('change-title', title);
        },
        nextCard() {
            if (this.currentCardIndex < this.data.length - 1) {
                this.$emit('change-progress', Math.round((this.currentCardIndex / this.data.length) * 100));
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
    position: absolute;
    top: 10px;
    left: 10px;
    font-size: 18px;
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


.flashcards-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100vh;
}

.card-container {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: calc(100% - 120px); /* subtract height of button and header containers */
    border-radius: 4px;
    perspective: 900px;
    background-image: none;
    box-sizing: border-box;
    padding: 20px;
    padding-top: 50px; /* add top padding */
    padding-bottom: 50px; /* add bottom padding */
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
    bottom: 10px; /* lesson bottom offset */
    right: 10px; /* lesson right offset */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999; /* make sure it appears above other elements */
}

#announcement-button {
    margin-bottom: 400px;
    margin-left: 10px;
    padding: 10px;
    border-radius: 4px;
    background-color: #f5f5f5;
    border: none;
    cursor: pointer;
    position: absolute; /* add position: absolute */
    bottom: 10px; /* lesson bottom offset */
    right: 90px; /* adjust right offset as needed */
}

.fa-solid.fa-bullhorn {
    font-size: 18px;
}
</style>
