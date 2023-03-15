<template>
    <header>
        <info-card
            title="Welcome to Let Learn"
            description="Learn anything, anytime, anywhere"
            :badge="{ text: 'Moderate', color: 'success' }"
        />
    </header>
    <div class="row">
        <h3>Lesson</h3>
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username"/>
        </div>
    </div>
    <div class="row">
        <h3>Course</h3>
        <div class="col-lg-4 col-md-6 col-12" v-for="course in courses">
            <LessonCard :title="course.name" :value="course.lesson_count + ' lessons'" :description="course.username"/>
        </div>
    </div>
    <div class="row" v-if="classes">
        <h2>Class</h2>
        <div class="col-lg-4 col-md-6 col-12">
            <LessonCard title="VNR" value="76 terms" description="<span
                  class='text-sm font-weight-bolder text-success'
                  /span> hailongvu"/>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <LessonCard title="HCM" value="78 terms" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+3%</span> since last week"/>
        </div>
        <div class="col-lg-4 col-md-6 col-12">
            <LessonCard title="SSC" value="108 terms" description="<span
                  class='text-sm font-weight-bolder text-success'
                  /span> HoaNK"/>
        </div>
    </div>

    <footer>
        <nav>
            <a href="#">Terms of Service</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Contact Us</a>
        </nav>
    </footer>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import InfoCard from "@/components/Cards/InfoCard.vue";

export default {
    name: "HomePage",
    components: {LessonCard, InfoCard},
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
    }
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
</style>
