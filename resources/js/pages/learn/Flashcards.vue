<template>
    <section class="card-container">
        <div class="card">
            <div class="front">
                <p>Front</p>
            </div>
            <div class="back">
                <p>Back</p>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "Flashcards"
}
</script>

<style scoped>
.card-container {
    width: 300px;
    height: 400px;
    border-radius: 4px;
    perspective: 900px;
    background-color:  transparent;
}
.card {
    position: relative;
    width: 100%;
    height: 100%;
    transition: all .7s;
    transform-style: preserve-3d;
}
.card-container:hover .card {
    transform: rotateY(-180deg);
}
.front {
    background: linear-gradient(90deg, #0086ff, #0066ff);
}
.back {
    background: linear-gradient(90deg, #ff2a78, #f100ae);
    transform: rotateY(-180deg);
}
.front, .back {
    position: absolute;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: bold;
    border-radius: 4px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.19),
    0 6px 6px rgba(0,0,0,0.23);
    backface-visibility: hidden;
}
</style>
