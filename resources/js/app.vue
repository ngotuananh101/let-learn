<template>
    <div id="overlay">
        <span class="loader"></span>
    </div>
    <router-view></router-view>
    <div class="position-fixed top-1 end-1" id="snackbar">
        <snackbar v-if="snackbar" :title="snackbar.title" date="Just now" :description="snackbar.message"
            :icon="{ component: 'ni ni-notification-70', color: 'danger' }" :color="snackbar.color"
            :close-handler="closeSnackbar" />
    </div>
</template>
<script>
import snackbar from './components/snackbar.vue';

export default {
    name: 'app',
    components: {
        snackbar,
    },
    data() {
        return {
            snackbar: null,
        }
    },
    methods: {
        closeSnackbar() {
            this.snackbar = null;
        },
        showSnackbar(message, color = 'danger', title = 'Notification') {
            this.snackbar = {
                message,
                color,
                title,
            };
            // set timeout to close snackbar
            setTimeout(() => {
                this.closeSnackbar();
            }, 4000);
        },
    },
}
</script>
<style lang="css">
#snackbar {
    z-index: 999999;
}

#overlay {
    position: fixed;
    top: 0;
    z-index: 99999;
    width: 100%;
    height: 100%;
    display: none;
    justify-content: center;
    align-items: center;
    background: var(--bs-gray-dark);
}

.loader,
.loader:before,
.loader:after {
    border-radius: 50%;
    width: 2.5em;
    height: 2.5em;
    animation-fill-mode: both;
    animation: bblFadInOut 1.8s infinite ease-in-out;
}

.loader {
    color: #FFF;
    font-size: 7px;
    position: relative;
    text-indent: -9999em;
    transform: translateZ(0);
    animation-delay: -0.16s;
}

.loader:before,
.loader:after {
    content: '';
    position: absolute;
    top: 0;
}

.loader:before {
    left: -3.5em;
    animation-delay: -0.32s;
}

.loader:after {
    left: 3.5em;
}

@keyframes bblFadInOut {

    0%,
    80%,
    100% {
        box-shadow: 0 2.5em 0 -1.3em
    }

    40% {
        box-shadow: 0 2.5em 0 0
    }
}
</style>