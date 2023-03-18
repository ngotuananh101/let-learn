<template>
    <header>
        <h2>FA22 - Fall Semester 2022</h2>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-4">
            <argon-avatar
                img="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                alt="Avatar" size="md" circular /></div>
                <div class="col-lg-1 col-md-2 col-4"><h6>Composed by user</h6></div>
            <h7>This is a course that contains lessons for the fall semester 2022</h7>
        </div>
    </header>
    <br>

    <div class="row">
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" value=""  description=""/>
        </div>
    </div>
    <argon-pagination>
        <argon-pagination-item prev />
        <argon-pagination-item label="1" active />
        <argon-pagination-item label="2" />
        <argon-pagination-item label="3" />
        <argon-pagination-item next />
    </argon-pagination>
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
import ArgonPagination from "@/components/Argons/ArgonPagination.vue";
import ArgonPaginationItem from "@/components/Argons/ArgonPaginationItem.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";
import { mapActions } from "vuex";
export default {
    name: "course",
    components: {
        LessonCard,
        ArgonPagination,
        ArgonPaginationItem,
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
    },
};
</script>

