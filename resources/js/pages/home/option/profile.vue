<template>
    <div class="card mt-5 mb-5">
        <div class="card-header me-5">
            <div class="row">
                <div class="col-md-2 p-3">
                    <img
                        :src="this.user.gravatar" class="img-fluid rounded-circle w-100" alt="...">
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <p class="card-text mb-1">Username: <span class="card-text fs-5 text-dark">{{
                                this.user.name
                            }}</span></p>
                        <p class="card-text mb-1">Email: {{ this.user.email }}</p>
                        <p class="card-text">Dob: {{ this.user.date_of_birth }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-1">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home"
                            type="button" role="tab" aria-controls="nav-home" aria-selected="true">Lesson
                    </button>
                    <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                            type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Course
                    </button>
                    <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                            type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Class
                    </button>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active pt-3 pb-3" id="nav-home" role="tabpanel"
                     aria-labelledby="nav-home-tab">
                    <div class="row">
                        <h3>Lesson</h3>
                        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons?.length ? lessons : []"
                             :key="lesson.id">
                            <router-link :to="`/lesson/${lesson.id}`">
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <p class="card-text text-primary">Title: {{ lesson.name }}</p>
                                        <p class="card-text">Quantity: {{ lesson.number_of_detail }}</p>
                                        <p class="card-text">Author: {{ lesson.author }}</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>

                    </div>
                    <div class="row">
                        <button type="button" class="btn btn-primary mt-3 col-1" @click="previous()"><i
                            class="fa-sharp fa-solid fa-arrow-left"></i></button>
                        <div class="col-10"></div>
                        <button type="button" class="btn btn-primary mt-3 col-1" @click="next()"><i
                            class="fa-sharp fa-solid fa-arrow-right"></i></button>
                    </div>
                </div>
                <div class="tab-pane fade pt-3 pb-3" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="row">
                        <h3>Course</h3>
                        <div class="col-lg-4 col-md-6 col-12" v-for="course in courses" :key="course.id">
                            <router-link :to="`/course/${course.id}`">
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <p class="card-text text-primary">Title: {{ course.name }}</p>
                                        <p class="card-text">Quantity: {{ course.number_of_detail }}</p>
                                        <p class="card-text">Author: {{ course.author }}</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade pt-3 pb-3" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="row">
                        <h3>Class</h3>
                        <div class="col-lg-4 col-md-6 col-12" v-for="classs in classes" :key="classs.id">
                            <router-link :to="`/class/${classs.id}`">
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <p class="card-text text-primary">Title: {{ classs.name }}</p>
                                    </div>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-item .active {
    border: none;
    border-bottom: 4px solid #5e72e4;
}

.nav-item:hover {
    border: none;
    border-bottom: 4px solid #5e72e4;
}
</style>

<script>
export default {
    name: "Profile",
    data() {
        return {
            page: 1,
            user: null,
            data: [],
            lessons: [],
            courses: [],
            classes: [],
            schools: [],
        };
    },
    created() {
        this.user = this.$store.getters['user/userData'].info;
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === "home/request") {
            } else if (mutation.type === "home/requestSuccess") {
                this.data = mutation.payload;
                this.lessons = this.data.lessons;
                // console.log(this.lessons.length);
                this.courses = this.data.courses;
                this.classes = this.data.classes;
                this.schools = this.data.schools;
                console.log(this.data);
            } else if (mutation.type === "home/requestFailure") {
            }
        });
        this.$store.dispatch("home/getUserInformation");
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        next() {
            if (this.data.lessons.length > 0) {
                this.page++;
                this.$store.dispatch("home/getUserInformation", this.page);
            } else {
                this.page = 1;
                console.log(this.page);
                this.$store.dispatch("home/getUserInformation", this.page);
            }
        },
        previous() {
            if (this.page > 1) {
                this.page--;
                this.$store.dispatch("home/getUserInformation", this.page);
            }
        }
    }
};
</script>
