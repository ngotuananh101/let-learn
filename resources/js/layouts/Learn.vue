<template>
    <Navbar :title="title" :showOptions="showOptions" :progress="progress" />
    <main class="main-content main-content-bg mt-5 d-flex justify-content-center align-items-center" >
        <div class="container">
            <router-view @change-progress="updateProgress"></router-view>
        </div>
    </main>
    <AppFooter />
</template>

<script>
import Navbar from "@/components/Navbars/LearnNavbar.vue";
export default {
    name: "Learn",
    components: {
        Navbar
    },
    data()  {
        return {
            title: 'Learn',
            showOptions: true,
            progress: 10
        }
    },
    methods: {
        updateProgress(data) {
            this.progress = data;
        }
    },
}
</script>

<style scoped>

</style>
