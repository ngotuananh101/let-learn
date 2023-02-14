<template>
    <div v-if="alert.message"
        :class="`alert ${alert.type} m-0 position-absolute w-100 border-0 text-white text-center fs-5`" style="">
        {{ alert.message }}</div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    <router-view></router-view>
</template>
<script>
import { mapState, mapActions } from 'vuex'

export default {
    name: 'app',
    computed: {
        ...mapState({
            alert: state => state.alert
        })
    },
    methods: {
        ...mapActions({
            clearAlert: 'alert/clear'
        })
    },
    watch: {
        $route(to, from) {
            this.clearAlert();
        }
    }
};
</script>
