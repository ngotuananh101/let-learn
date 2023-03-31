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
    <a class="float" data-bs-toggle="modal" data-bs-target="#floatModal">
        <i class="fa fa-plus my-float">
        </i>
    </a>
    <div class="modal fade" id="floatModal" aria-hidden="false" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-4">
                            <div class="card" style="width: 14rem;" @click="this.createLesson">
                                <img src="https://cdn-icons-png.flaticon.com/512/1575/1575305.png" class="card-img-top"
                                    alt="...">
                                <div class="card-body">
                                    <p class="btn btn-success">Create lesson</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-4">
                            <div class="card" style="width: 14rem;">
                                <img src="https://cdn-icons-png.flaticon.com/512/2103/2103423.png" class="card-img-top"
                                    alt="...">
                                <div class="card-body">
                                    <button class="btn btn-success" data-bs-target="#createCourse" data-bs-toggle="modal"
                                        data-bs-dismiss="modal">Create course</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createCourse" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel2">Create course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="form-label mt-2 fs-6">Name</label>
                        <argon-input id="name" type="text" name="name"
                            placeholder="Enter a name. Example:'MAE Chapter 1' " />
                    </div>
                    <div>
                        <label class="form-label mt-2 fs-6">Description</label>
                        <argon-input id="description" type="text" name="description" placeholder="Add a description..." />
                    </div>
                </div>
                <div class="modal-footer">
                    <argon-button color="success" @click="this.createCourse">Create</argon-button>
                    <argon-button class="btn btn-secondary" data-bs-target="#floatModal" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Back</argon-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import InfoCard from "@/components/Cards/InfoCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonInput from "@/components/Argons/ArgonInput.vue";

export default {
    name: "HomePage",
    components: { LessonCard, InfoCard, ArgonButton, ArgonInput },
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
        //     class.js => {
        //         console.log(class.js);
        //         this.class.js = class.js;
        //     }
        // );

    },
    methods: {
        createLesson() {
            this.$router.push({ name: 'home.lesson.add' });
        },
        createCourse() {
            let name = document.getElementById('name').value;
            let description = document.getElementById('description').value;
            if (name === '' || description === '') {
                alert('Please fill all fields');
            } else {
                this.$store.dispatch('course/createCourse', { name: name, description: description }).then(() => {
                    this.$router.push({ name: 'home.index' });
                });
                // Toggle the createCourseModal modal after a short delay
                setTimeout(() => {
                    $('#createCourseModal').modal('toggle');
                }, 500);
            }
        }
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
