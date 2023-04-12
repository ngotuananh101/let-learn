<template>
    <div class="my-4">
        <div class="card">
            <div class="card-body text-success text-sans-serif text-2xl">
                Learn anything, anytime, anywhere
            </div>
        </div>
    </div>
    <div class="mt-5">
        <div class="row">
            <h3>Lesson</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons" :key="lesson.id">
                <router-link :to="`/lesson/${lesson.id}`">
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="card-text text-primary">Title: {{ lesson.description }}</p>
                            <p class="card-text">Author: {{ lesson.name }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row mt-5">
            <h3>Course</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="course in courses" :key="course.id">
                <router-link :to="`/course/${course.id}`">
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="card-text text-primary">Title: {{ course.description }}</p>
                            <p class="card-text">Author: {{ course.name }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row mt-5">
            <h3>Other Lessons</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="ol in other_lesson" :key="ol.id">
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="card-text text-primary">Title: {{ ol.description }}</p>
                        <p class="card-text">Author: {{ ol.name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 mt-5">
            <h3>Other Courses</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="other_courses in other_course" :key="other_courses.id">
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="card-text text-primary">Title: {{ other_courses.description }}</p>
                        <p class="card-text">Author: {{ other_courses.name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    name: 'home',
    data() {
        return {
            user : JSON.parse(localStorage.getItem('user')),
            unsubscribe: null,
            lessons: [],
            courses: [],
            other_lesson: [],
            other_course: [],
        }
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
        this.$store.dispatch("home/getHome");
    },


}
</script>
<style scoped>
.card {
    flex: 1;
}
</style>
