<template>
    <div class="flashcards-container">
        <!-- add card count display -->
        <div class="card-count">{{ cardsCount.currentCardIndex + 1 }} / {{ cardsCount.totalCards }}</div>
        <!-- add settings button outside of card container -->
        <button @click="toggleSettings" class="settings-button">Settings</button>

        <section class="card-container">
            <div class="card d-flex justify-content-center align-items-center p-5" @click="rotateCard">
                <p id="card-title" class="fs-3"></p>
            </div>
        </section>
        <div class="buttons-container">
            <button @click="previousCard">Previous</button>
            <button @click="nextCard">Next</button>
        </div>
        <!-- add settings menu popup -->

        <div class="buttons">
            <div v-if="showSettings" class="settings-overlay">
                <div class="settings-menu">
                    <!-- add settings button -->
                    <button class="settings-close-button" @click="toggleSettings">×</button>
                    <h2>Tùy chọn</h2>
                    <h6>Sắp xếp thẻ</h6>
                    <p>Sắp xếp thẻ của bạn để tập trung vào những thuật ngữ cần chú tâm học. Tắt tính năng sắp xếp nếu
                        bạn muốn nhanh chóng ôn lại các thẻ ghi nhớ.</p>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                        <label class="form-check-label" for="flexSwitchCheckDefault">Âm thanh</label>
                    </div>
                    <!-- add your settings options here -->
                    <button @click="toggleSettings">Close</button>
                </div>
            </div>
            <div>
                <button id="quit-button" onclick="window.location.href = '/'">Quit</button>
                <button id="announcement-button" @click="speak(cards[currentCardIndex].front)"><i
                    class="fa-solid fa-bullhorn"></i></button>
            </div>
        </div>
    </div>

</template>


<script>
export default {
    name: "Flashcards",
    data() {
        return {
            cards: [
                {
                    front: "Which statement is true?\n" +
                        "a. Current liabilities include accounts receivable, unearned revenues, and salaries payable.\n" +
                        "b. Current liabilities include prepayment, unearned revenues, and salaries payable.\n" +
                        "c. Current liabilities include revenue, unearned revenues, and salaries payable.\n" +
                        "d. Current liabilities include accounts payable, unearned revenues, and salaries payable.\n" +
                        "e. Current liabilities include expense, unearned revenues, and salaries payable.",
                    back: "d"
                },
                {
                    front: "Which accounts don't need to do closing entries?\n" +
                        "a. Revenue\n" +
                        "b. Income Summary\n" +
                        "c. Prepaid expense\n" +
                        "d. Withdrawals\n" +
                        "e. Expense",
                    back: "c"
                },
                {
                    front: "The special account used only in the closing process to temporarily hold the amounts of revenues and expenses before the net difference is added to (or subtracted from) the owner's capital account is the:\n" +
                        "a. Income Summary account.\n" +
                        "b. Closing account.\n" +
                        "c. Balance column account.\n" +
                        "d. Profit accounts.\n" +
                        "e. Loss accounts.",
                    back: "a"
                }
            ],
            currentCardIndex: 0,
            currentSide: 'front',
            showSettings: false // add showSettings data property
        };
    },
    mounted() {
        document.getElementById('card-title').innerHTML = this.cards[this.currentCardIndex].front;
    },
    methods: {
        nextCard() {
            if (this.currentCardIndex < this.cards.length - 1) {
                this.currentCardIndex++;
            } else {
                this.currentCardIndex = 0;
            }
            document.getElementById('card-title').innerHTML = this.cards[this.currentCardIndex].front;
        },
        previousCard() {
            if (this.currentCardIndex > 0) {
                this.currentCardIndex--;
            } else {
                this.currentCardIndex = this.cards.length - 1;
            }
            document.getElementById('card-title').innerHTML = this.cards[this.currentCardIndex].front;
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
                document.getElementById('card-title').innerHTML = this.cards[this.currentCardIndex].back;
                this.currentSide = 'back';
            } else {
                card.classList.remove('rotate');
                document.getElementById('card-title').classList.remove('card-title-transition');
                document.getElementById('card-title').innerHTML = this.cards[this.currentCardIndex].front;
                this.currentSide = 'front';
            }
        }
    },
    computed: {
        cardsCount() { // add cardsCount computed property
            return {
                currentCardIndex: this.currentCardIndex,
                totalCards: this.cards.length
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

.settings-button {
    position: absolute;
    top: 10px;
    right: 10px;
    z-index: 999; /* make sure it appears above other elements */
    left: auto; /* remove left property */
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
    z-index: 998; /* make sure it appears below the settings button */
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
    font-size: 24px;
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
    bottom: 10px; /* set bottom offset */
    right: 10px; /* set right offset */
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
    bottom: 10px; /* set bottom offset */
    right: 90px; /* adjust right offset as needed */
}

.fa-solid.fa-bullhorn {
    font-size: 18px;
}
</style>
