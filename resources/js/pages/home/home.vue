
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
            <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
                <router-link :to="`/lesson/${lesson.id}`">
                    <div class="card mt-4">
                        <div class="card-body">
                            <p class="card-text text-primary">{{ lesson.name }}</p>
                            <p class="card-text">Quantity: {{ lesson.detail_count }}</p>
                            <p class="card-text">Author: {{ lesson.username }}</p>
                        </div>
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row">
            <h3>Course</h3>
            <div class="col-lg-4 col-md-6 col-12" v-for="course in courses">
                <router-link :to="`/course/${course.id}`">
                    <LessonCard :title="course.name" :value="course.lesson_count + ' lessons'"
                                :description="course.username"/>
                </router-link>
            </div>
        </div>
        <div v-if="classes" class="row">
            <h2>Class</h2>
            <div class="col-lg-4 col-md-6 col-12" v-for="classes in classes">
                <LessonCard :title="classes.name" :value="classes.classes_count + ' courses'"/>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
    name: 'home',
    data() {
        return {
            unsubscribe: null,
            lessons: [],
            courses: [],
            classes: []
        }
    },
    created() {
        this.$store.dispatch("home/getHome").then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
            }
        );
    },


}
</script>
