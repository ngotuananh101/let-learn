<template>
    <header>
        <info-card title="Welcome to Let Learn" description="Learn anything, anytime, anywhere"
            :badge="{ text: 'Moderate', color: 'success' }" />
    </header>
    <div class="row">
        <h3>Lesson</h3>
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
            <router-link :to="`/lesson/${lesson.id}`">
                <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username" />
            </router-link>
        </div>
    </div>
    <div class="row">
        <h3>Course</h3>
        <div class="col-lg-4 col-md-6 col-12" v-for="course in courses">
            <router-link :to="`/course/${course.id}`">
                <LessonCard :title="course.name" :value="course.lesson_count + ' lessons'" :description="course.username" />
            </router-link>
        </div>
    </div>
    <div class="row" v-if="classes">
        <h2>Class</h2>
        <div class="col-lg-4 col-md-6 col-12" v-for="classes in classes">
            <LessonCard :title="classes.name" :value="classes.classes_count + ' courses'" />
        </div>
    </div>
    <a class="float" data-bs-toggle="modal" data-bs-target="#modal">
        <i class="fa fa-plus my-float">
        </i>
    </a>
    <div class="modal" id="modal" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-4">
                            <div class="card" style="width: 14rem;" @click="this.createLesson">
                                <img src="https://www.flaticon.com/free-icon/lesson_1575305?term=lesson&page=1&position=5&origin=search&related_id=1575305"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Create Lesson</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-4">
                            <div class="card" style="width: 14rem;" @click="this.createCourse">
                                <img src="https://www.flaticon.com/free-icon/folder_3767084?term=folder&page=1&position=4&origin=search&related_id=3767084"
                                    class="card-img-top" alt="...">
                                <div class="card-body">
                                    <p class="card-text">Create Course</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div>
                        <label class="form-label mt-2 fs-6">Name</label>
                        <argon-input id="name" type="text" name="name"
                            placeholder="Enter a name. Example:'MAE Chapter 1' " />
                    </div>
                    <div>
                        <label class="form-label mt-2 fs-6">Description</label>
                        <argon-input id="description" type="text" name="description" placeholder="Add a description..." />
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-5">
                                <argon-button color="success" variant="gradient" full-width class="my-2 btn-lg fs-6"
                                    @click="this.createCourse">Create</argon-button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import InfoCard from "@/components/Cards/InfoCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";

export default {
    name: "HomePage",
    components: { LessonCard, InfoCard, ArgonButton },
    data() {
        return {
            lessons: null,
            courses: null,
            classes: null
        };
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.$store.dispatch('home/getLesson').then(
            lesson => {
                console.log(lesson);
                this.lessons = lesson;
            }
        );
        this.$store.dispatch('home/getCourse').then(
            course => {
                console.log(course);
                this.courses = course;
            }
        );
        // this.$store.dispatch('home/getClass').then(
        //     classes => {
        //         console.log(classes);
        //         this.classes = classes;
        //     }
        // );

    },
    methods: {
        createLesson() {
            this.$router.push({ name: 'home.lesson.add' });
        },
        //     createCourse() {
        //         let name = document.getElementById('name').value;
        //         let description = document.getElementById('description').value;
        //         if (name === '' || description === '') {
        //             alert('Please fill all fields and add at least 3 cards');
        //         } else {
        //             this.$store.dispatch('home/createCourse', { name: name, description: description }).then(() => {
        //                 this.$router.push({ name: 'home.index' });
        //             });
        //         }
        //     }
    },
};
</script>

<style scoped>
header nav {
    display: flex;
    justify-content: flex-end;
}

header nav a {
    margin-left: 1em;
    text-decoration: none;
    color: #555;
    font-weight: bold;
    font-size: 1.2rem;
}

header nav a:hover {
    color: #0077ff;
}

header h1 {
    font-size: 4rem;
    margin-top: 1em;
}

header p {
    font-size: 1.5rem;
    margin-bottom: 2em;
    color: #555;
}

header form {
    display: flex;
    margin-top: 2em;
}

header form input[type="text"] {
    flex-grow: 1;
    padding: 0.5em;
    border: none;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
}

header form button {
    padding: 0.5em 1em;
    background-color: #0077ff;
    color: #fff;
    border: none;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .075);
}

section ul {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    list-style: none;
    padding: 0;
    margin: 0;
}

.row h2 {
    margin-top: 30px;
    margin-bottom: 25px;
}

section li {
    width: 30%;
    margin-bottom: 2em;
}


.course-image img {
    display: block;
    width: 100%;
}


.course-details h3 {
    margin-top: 0;
    font-size: 1.5rem;
}

.course-details p {
    margin-top: 1em;
    color: #555;
}

section blockquote {
    font-size: 1.2rem;
    margin-bottom: 1em;
}

section cite {
    font-weight: bold;
}

footer nav {
    margin-top: 2em;
    display: flex;
    justify-content: center;
}

footer nav a {
    text-decoration: none;
    color: #555;
    font-size: 1.2rem;
    margin-right: 1em;
}

footer nav a:hover {
    color: #0077ff;
}

.float {
    position: fixed;
    z-index: 2;
    -webkit-appearance: none;
    background: none;
    outline: none;
    border: none;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 3px;
    border-radius: 2px;
    transition: all 0.5s ease;
    font-weight: 600;
    font-size: 14px;
    width: 60px;
    height: 60px;
    bottom: 40px;
    right: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 50px;
    background-color: #0C9;
    color: #FFF;
    box-shadow: 2px 2px 3px #999;
}

.my-float {
    margin-top: 2px;
    margin-left: 5px;
}
</style>
