<template>
    <div class="row">
        <div class="col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username"/>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";

export default {
    name: "profile_lesson",
    components: {LessonCard},
    data() {
        return {
            lessons: null,
        };
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.lessons = mutation.payload.lessons;
                this.courses = mutation.payload.courses;
                this.other_lesson = mutation.payload.other_lesson;
                console.log(this.other_lesson);
                this.other_course = mutation.payload.other_course;
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getLessonById");
    },
};
</script>
