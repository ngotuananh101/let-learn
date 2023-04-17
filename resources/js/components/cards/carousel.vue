<template>
    <div class="card card-carousel overflow-hidden p-0 h-100" :class="this.class" :style="this.style">
        <div
            id="carouselExampleCaptions"
            class="carousel slide h-100"
            data-bs-ride="carousel"
        >
            <div class="carousel-inner border-radius-lg h-100">
                <div
                    v-for="(
            { img, title, description}, index
          ) of items"
                    :key="index"
                    :class="`carousel-item h-100 ${index === 0 ? 'active' : ''}`"
                    :style="{
            backgroundImage: `url('${img}')`,
            backgroundSize: 'cover',
          }"
                >
                    <span class="mask bg-gradient-dark opacity-5"></span>
                    <div
                        class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
                    >
                        <h3 class="text-white mb-1">{{ title }}</h3>
                        <p>-----</p>
                        <p class="fs-5">
                            {{ description }}
                        </p>
                    </div>
                </div>
            </div>
            <button
                class="carousel-control-prev w-5 me-3"
                type="button"
                data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev"
            >
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button
                class="carousel-control-next w-5 me-3"
                type="button"
                data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next"
            >
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Carousel",
    props: {
        items: {
            type: Array,
            required: true,
            img: String,
            title: String,
            description: String,
            icon: {
                type: Object,
                component: String,
                background: String,
            },
            default: () => [],
        },
        class: {
            type: String,
            default: "",
        },
        style: {
            type: Object,
            default: () => {
                return {};
            }
        },
    },
};
</script>
