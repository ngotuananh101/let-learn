<template>
    <div class="row">
        <div class="col-md-6 col-12">
            <LessonCard :title="'jgdfgdfgd'" :value="'jgdfgdfgd' + ' terms'" :description="'jgdfgdfgd'"/>
        </div>
        <div class="col-md-6 col-12">
            <LessonCard :title="'jgdfgdfgd'" :value="'jgdfgdfgd' + ' terms'" :description="'jgdfgdfgd'"/>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";

export default {
    name: "MyComponent",
    components: {LessonCard},
    beforeMount() {
        this.$store.dispatch('home/getLesson').then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
            }
        );
    }
};
</script>
