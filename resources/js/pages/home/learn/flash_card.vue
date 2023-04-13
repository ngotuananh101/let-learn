<template>
    <div class="container pt-5">
    <div class="card" id="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="card-subtitle text-muted">{{ cardsCount.currentCardIndex + 1 }} /
                        {{ cardsCount.totalCards }}</h6>
                </div>
                <div @click="speak">
                    <i class="fa-solid fa-volume fs-4"></i>
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
</template>


<script>

export default {
    name: "flash_card",
    data() {
        return {
            id: this.$route.params.id,
            data: null,
            currentCardIndex: 0,
            currentSide: "front",
            showSettings: false // add showSettings data property
        };
    },

    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "learn/request") {
            } else if (mutation.type === "learn/requestSuccess") {
                this.data = mutation.payload;
                console.log(this.data);
                document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
            } else if (mutation.type === "learn/requestFailure") {
            }
        });
        this.$store.dispatch("learn/getFlashCard", this.id);
    },
    methods: {
        updateTitle(title) {
            this.$emit("change-title", title);
        },
        nextCard() {
            console.log(this.currentCardIndex);
            if (this.currentCardIndex < this.data.notLearn.length-1) {
                this.currentCardIndex++;
                this.$emit("change-progress", Math.round((this.currentCardIndex / this.data.notLearn.length) * 100));
            } else {
                this.currentCardIndex = 0;
                this.$emit("change-progress", 0);
            }
            document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
                this.$emit("change-progress", Math.round((this.currentCardIndex / this.data.notLearn.length) * 100));
            } else {
                this.currentCardIndex = this.data.notLearn.length - 1;
            }
            document.getElementById("text").innerHTML = this.data.notLearn[this.currentCardIndex].definition;
        },
        toggleSettings() { // add toggleSettings method
            this.showSettings = !this.showSettings;
        },
        // text to speech
        speak() {
            const msg = new SpeechSynthesisUtterance();
            msg.text = document.getElementById("text").innerHTML;
            window.speechSynthesis.speak(msg);
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
        }
    },
    computed: {
        cardsCount() { // add cardsCount computed property
            return {
                currentCardIndex: this.currentCardIndex,
                totalCards: this.data ? this.data.notLearn.length : 0
            };
        }
    }
};
</script>


<style scoped>
.card{
    transition: all 0.3s;
    transform-style: preserve-3d;
}
.rotate {
    transform: rotateY(360deg);
}
.card-text{
    white-space: pre-line;
}
</style>
