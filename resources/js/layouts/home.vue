<template>
    <div class="container-fluid p-0 top-0 position-sticky z-index-sticky">
        <navbar is-blur="blur start-0 end-0 shadow m-0" btn-background="bg-gradient-success"
                :dark-mode="true"/>
    </div>
    <main class="main-content main-content-bg mt-0">
        <div class="container">
            <router-view></router-view>
        </div>
    </main>
</template>
<script lang="js">
import homeNavbar from '../components/navbars/home-navbar.vue'

export default {
    name: 'home layout',
    components: {
        navbar: homeNavbar
    }
}
</script>
<style lang="">

</style>
