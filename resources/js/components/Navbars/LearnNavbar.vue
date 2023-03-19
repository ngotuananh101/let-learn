<template>
    <div class="w-100 bg-white container-fluid d-flex justify-content-between align-items-center py-3">
        <router-link :to="'/'" class="btn m-0 rounded-circle d-flex justify-content-center align-items-center" style="width: 2.5rem; height: 2.5rem;">
            <i class="fa-solid fa-chevron-left"></i>
        </router-link>
        <div class="fs-4 fw-bold">
            {{ title }}
        </div>
        <button class="btn m-0" v-if="this.showOptions">
            Options
        </button>
    </div>
    <div class="progress">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            :style="{ width: progress + '%' }"
            :aria-valuenow="progress"
            aria-valuemin="0"
            aria-valuemax="100"
        >
        </div>
    </div>
</template>

<script>
export default {
    name: "LearnNavbar",
    props: {
        title: {
            type: String,
            required: true
        },
        showOptions: {
            type: Boolean,
            required: true
        },
        progress: {
            type: Number,
            required: true
        },
    },
}
</script>
