<template>
    <div class="row mt-3">
        <div class="col-12">
            <h3>Search results for "{{ query }}"</h3>
        </div>
        <div class="col-12">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                            type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Lesson
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane"
                            type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Course
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane"
                            type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Post
                    </button>
                </li>
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab"
                     tabindex="0">
                    <div class="row">
                        <div class="col-md-3 col-6 mt-3" v-for="lesson in lessons">
                            <div class="card" @click="viewLesson(lesson.id)">
                                <div class="card-body">
                                    <h5 class="card-title">{{ lesson.name }}</h5>
                                    <p class="card-text">{{ lesson.detail_count }} terms</p>
                                    <div>
                                        <img :src="lesson.author.avatar" alt="user logo"
                                             class="avatar-xs rounded-circle">
                                        <span class="ms-2 fs-6">{{ lesson.author.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab"
                     tabindex="0">
                    <div class="row">
                        <div class="col-md-3 col-6 mt-3" v-for="course in courses">
                            <div class="card" @click="viewCourses(course.id)">
                                <div class="card-body">
                                    <h5 class="card-title">{{ course.name }}</h5>
                                    <p class="card-text">{{ course.lesson_count }} lessons</p>
                                    <div>
                                        <img :src="course.author.avatar" alt="user logo"
                                             class="avatar-xs rounded-circle">
                                        <span class="ms-2 fs-6">{{ course.author.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab"
                     tabindex="0">
                    <div class="row">
                        <div class="col-md-4 col-12 mt-3" v-for="post in posts">
                            <div class="card" @click="viewPost(post.id)">
                                <div class="card-body">
                                    <span class="text-muted">{{ post.created_at }}</span>
                                    <h5 class="card-title">{{ post.title }}</h5>
                                    <p class="card-text">{{ post.content.slice(30) + "..." }}</p>
                                    <div>
                                        <img :src="post.author.avatar" alt="user logo" class="avatar-xs rounded-circle">
                                        <span class="ms-2 fs-6">{{ post.author.name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "search",
    data() {
        return {
            query: this.$route.params.query,
            unsubscribe: null,
            lessons: [],
            courses: [],
            posts: [],
        }
    },
    created() {
        this.$setPageTitle(`Search results for "${this.query}"`);
        this.unsubscribe = this.$store.subscribe((mutation, state) => {
            if (mutation.type === 'home/search') {
                this.lessons = mutation.payload.lessons;
                this.courses = mutation.payload.courses;
                this.posts = mutation.payload.posts;
                this.query = mutation.payload.query;
                this.$setPageTitle(`Search results for "${this.query}"`);
            }
        });
        this.$store.dispatch('home/search', this.query);
    },
    methods: {
        viewLesson(id) {
            this.$router.push({name: 'lesson.index', params: {id}});
        },
        viewCourses(id) {
            this.$router.push({name: 'course.index', params: {id}});
        },
        viewPost(id) {
            this.$router.push({name: 'forum.post', params: {id}});
        }
    },
}
</script>

<style scoped>

</style>
