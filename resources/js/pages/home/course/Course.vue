<template>
    <header>
        <h2>FA22 - Fall Semester 2022</h2>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-4 mt-4">
                <argon-avatar
                    img="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                    alt="Avatar" size="md" circular />
            </div>
            <div class="col-lg-1 col-md-2 col-4 mt-4">
                <h6>Composed by user</h6>
            </div>
            <h7>This is a course that contains lessons for the fall semester 2022</h7>
        </div>
    </header>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-4">
            <router-link :to="{ name: 'home.course.edit', params: { id: lesson_id } }">
                <argon-button class="fa-light fa-pen-to-square"></argon-button>
            </router-link>
        </div>
        <div class="col-lg-4 col-md-4 col-4">
            <argon-button class="fa fa-trash" @click="this.delete"></argon-button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-12 mt-4" v-for="lesson in lessons">
            <router-link :to="`/l/${lesson.id}`">
                <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username" />
            </router-link>
        </div>
    </div>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";
import { mapActions } from "vuex";
export default {
    name: "course",
    components: {
        LessonCard,
        ArgonButton,
        ArgonAvatar,
    },
    data() {
        return {
            lessons: null,
            course_id: this.$route.params.id,
        }
    },
    title() {
        return "Home - " + document.getElementsByTagName("meta")["title"].content;
    },
    beforeMount() {
        this.getLesson(this.course_id).then((response) => {
            this.lessons = response;
        });
    },
    methods: {
        ...mapActions({
            getLesson: "home/getLessonByCourseId",
        }),
        delete() {
            this.$swal({
                icon: "question",
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = this.selected_id;
                    this.$store.dispatch('home/deleteCourse', id).then(() => {
                        document.getElementById('edit-close').click();
                        // this.$store.dispatch('home/index').then(() => {
                        //     this.sets = this.$store.state.adminSet.sets;
                        // });
                    });
                    // remove selected row
                    this.table.rows({selected: true}).remove().draw();
                    // close modal
                    document.getElementById('edit-close').click();
                }
            });
        },
    },
};
</script>

