<template>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <router-view></router-view>
    <div class="position-absolute top-1 end-1">
        <argon-snackbar
            v-if="snackbar"
            title="Notification"
            date="Just now"
            :description="snackbar.message"
            :icon="{ component: 'ni ni-notification-70', color: 'danger' }"
            :color="snackbar.color"
            :close-handler="closeSnackbar"
        />
    </div>
</template>
<script>
import {mapActions, mapState} from "vuex";
import ArgonSnackbar from "@/components/Argons/ArgonSnackbar.vue";

export default {
    name: 'main app',
    components: {
        ArgonSnackbar,
    },
    data() {
        return {
            snackbar: "",
        };
    },
    beforeMount() {
        this.index();
    },
    mounted() {
        if (this.meta) {
            // set meta title
            document.querySelector('meta[name="title"]').setAttribute('content', this.meta.name);
            // set meta description
            document.querySelector('meta[name="description"]').setAttribute('content', this.meta.description);
            // set meta keywords
            document.querySelector('meta[name="keywords"]').setAttribute('content', this.meta.keywords);
            // add html code to head
            document.querySelector('head').insertAdjacentHTML('beforeend', this.meta.header);
        }
    },
    created(){
    },
    title() {
        return this.meta.name;
    },
    methods: {
        ...mapActions('meta', ['index']),
        closeSnackbar() {
            this.snackbar = "";
        },
    },
    computed: {
        ...mapState({
            meta: state => state.meta.meta,
        }),
    },
};
</script>

<style scoped>
    .notification{
        position: relative;
        z-index: 9999;
        top: 10px;
        right: 10px;
    }
</style>
