<template>
    <div class="card">
        <div class="card-header me-5 pb-0">
            <div class="row">
                <div class="col-md-2 p-3">
                    <img
                        :src="this.user.avatar" class="img-fluid rounded-circle w-100" alt="...">
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <p class="card-text mb-1">Username: <span class="card-text fs-5 text-dark">{{ this.user.username }}</span></p>
                        <p class="card-text mb-1">Email: {{ this.user.email }}</p>
                        <p class="card-text">Dob: {{ this.user.date_of_birth }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body pt-1">
            <ul class="nav nav-tabs border-0">
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.lessons'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.lessons' }" style="color: black"
                                 aria-current="page">Lesson
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.courses'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.courses' }" style="color: black"
                                 aria-current="page">Course
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{ name: 'home.profile.class.js'}" class="nav-link"
                                 :class="{ active: $route.name === 'home.profile.class.js' }" style="color: black"
                                 aria-current="page">Class
                    </router-link>
                </li>
            </ul>
            <div class="row mt-4">
                <router-view></router-view>
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
            user: null,
            lessons: null,
            courses: null,
            classes: null
        };
    },
    created() {
        this.user = {
            avatar: "https://via.placeholder.com/150",
            username: "JaneDoe",
            email: "janedoe@example.com",
            date_of_birth: "1990-01-01"
        };

        this.lessons = [
            { id: 1, title: "Lesson 1", description: "Lorem ipsum dolor sit amet." },
            { id: 2, title: "Lesson 2", description: "Consectetur adipiscing elit." },
            { id: 3, title: "Lesson 3", description: "Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua." }
        ];

        this.courses = [
            { id: 1, title: "Course 1", description: "Ut enim ad minim veniam." },
            { id: 2, title: "Course 2", description: "Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat." },
            { id: 3, title: "Course 3", description: "Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur." }
        ];

        this.classes = [
            { id: 1, title: "Class 1", description: "Excepteur sint occaecat cupidatat non proident." },
            { id: 2, title: "Class 2", description: "Sunt in culpa qui officia deserunt mollit anim id est laborum." },
            { id: 3, title: "Class 3", description: "Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit." }
        ];
    },
};
</script>
