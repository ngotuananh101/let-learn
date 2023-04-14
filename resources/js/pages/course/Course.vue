<template>
    <div class="row">
        <!-- <h2>{{ data.course.name }}</h2> -->
        <div class="col-12 mb-3 row align-items-center justify-content-center">
            <div class="col-2">
                <img src="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                    class="rounded-circle" alt="Avatar" style="width: 100px" />
            </div>
            <div class="col-6">
            </div>
            <div class="col-4 p-0">
                <div class="dropdown show">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    </button>
                    <ul class="dropdown-menu">
                        <router-link :to="{ name: 'course.edit', params: { id: id } }">
                            <li><a class="dropdown-item" href="#">Edit</a></li>
                        </router-link>
                        <li><a class="dropdown-item" @click="deleteCourse">
                                Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div>
        <!-- show description -->
        <!-- <p class="m-0">{{ data.course.description }}</p> -->
    </div>
    <div class="row">
        <h3>Course</h3>
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons" :key="lesson.id">
            <router-link :to="`/lesson/${lesson.id}`">
                <div class="card mt-4">
                    <div class="card-body">
                        <p class="card-text text-primary">Title: {{ lesson.name }}</p>
                        <p class="card-text">Author: {{ lesson.number_of_detail }}</p>
                    </div>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: "course",
    props: ["course_id"],
    components: {

    },
    data() {
        return {
            data: null,
            course: null,
            lessons: null,
            user: null,
            id: this.$route.params.id,
            type: null,
        }
    },
    title() {
        return ("Home - " + document.getElementsByTagName("meta")["title"].content);
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "course/request") {
            } else if (mutation.type === "course/success") {
                if (!this.type) {
                    this.data = mutation.payload;
                    this.lessons = this.data.lessons = mutation.payload.lessons;
                    console.log(this.lessons);
                }
                if (this.type === 'delete') {
                    this.$root.showSnackbar('Delete course successfully', 'success');
                    this.$router.push({ name: 'home.home' });
                }
            } else if (mutation.type === "course/failure") {
                this.$root.showSnackbar(mutation.payload, 'danger');
            }
        });
        this.$store.dispatch("course/getLessonByCourseId", this.id);
    },
    methods: {
        deleteCourse() {
            if (confirm('Are you sure?')) {
                this.type = 'delete';
                this.$store.dispatch('course/deleteCourse', this.id);
            }
        },
    },
};
</script>

<style scoped></style>
