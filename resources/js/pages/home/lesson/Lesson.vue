<template>
    <header>
        <h2>HCM201</h2>
        <div class="row">
            <div class="col-lg-1 col-md-2 col-4">
                <argon-avatar
                    img="https://gcavocats.ca/wp-content/uploads/2018/09/man-avatar-icon-flat-vector-19152370-1.jpg"
                    alt="Avatar" size="md" circular />
            </div>
            <div class="col-lg-1 col-md-2 col-4">
                <h6>Composed by user</h6>
            </div>
            <h7>This is a lesson to pass HCM201</h7>
        </div>
    </header>
    <br>
    <div class="row">
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Flash Card</argon-button></div>
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Learn</argon-button></div>
        <div class="col-lg-2 col-md-2 col-4"><argon-button class="w-100">Test</argon-button></div>
    </div>
    <br>
    <br>
    <h5>Number of terms in this lesson: 100</h5>

    <br>
    <h6>Relearn: 40</h6>
    <h7>You have already started learning these terms. Continue to promote offline!</h7>
    <div class="row" v-if=lessonDetails>
        <LessonCard :title="lessonDetails.terms" :fontsize="'16'" value="" :description="lessonDetails.description" />
    </div>

    <br>
    <h6>Not yet learn: 30</h6>
    <h7>You haven't learned these terms yet! Let's start learning now!</h7>
    <div class="row">
        <LessonCard title="A" :fontsize="'16'" value="" description="QN=10 Một trong những giá trị của văn hoá phương Tây được Hồ Chí Minh tiếp thu để hình thành tư tưởng của mình là:
                        a. Tư tưởng văn hoá dân chủ và cách mạng của cách mạng Pháp và cách mạng Mỹ.
                        b. Những mặt tích cực của Nho Giáo
                        c. Tư tưởng vị tha của Phật giáo
                        d. Chủ nghĩa Tam dân của Tôn Trung Sơn." />
        <LessonCard title="D" :fontsize="'16'" value="" description="QN=15 Để chuẩn bị thành lập Đảng Cộng sản Việt Nam, Nguyễn Ái Quốc đã từ Liên Xô về Trung Quốc năm nào?
                        a. 1925
                        b. 1927
                        c. 1923
                        d. 1924" />
        <LessonCard title="C" :fontsize="'16'" value="" description="QN=17 Yếu tố nào sau đây ảnh hưởng đến sự hình thành tư tưởng Hồ Chí Minh:
                        a. Lý luận và kinh nghiệm các cuộc cách mạng điển hình phương Tây
                        b. Kinh nghiệm các cuộc cách mạng điển hình ở phương Đông
                        c. Lý luận và thực tiễn các cuộc cách mạng điển hình trên thế giới" />
    </div>

    <br>
    <h6>Other lessons from this user</h6>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-12" v-for="lesson in lessons">
            <LessonCard :title="lesson.name" :value="lesson.detail_count + ' terms'" :description="lesson.username"/>
        </div>
    </div>
    <!-- <argon-pagination>
        <argon-pagination-item prev />
        <argon-pagination-item label="1" active />
        <argon-pagination-item label="2" />
        <argon-pagination-item label="3" />
        <argon-pagination-item next />
    </argon-pagination> -->
</template>

<script>
import LessonCard from "@/components/Cards/LessonCard.vue";
// import ArgonPagination from "@/components/Argons/ArgonPagination.vue";
// import ArgonPaginationItem from "@/components/Argons/ArgonPaginationItem.vue";
import ArgonButton from "@/components/Argons/ArgonButton.vue";
import ArgonAvatar from "@/components/Argons/ArgonAvatar.vue";

export default {
    name: "lesson",
    components: {
        LessonCard,
        // ArgonPagination,
        // ArgonPaginationItem,
        ArgonButton,
        ArgonAvatar,
    },
    data() {
        return {
            lessons: null,
            // lessonDetails: null,
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
        // this.$store.dispatch('home/getLessonDetail').then(
        //     lessonDetail => {
        //         console.log(lessonDetail);
        //         this.lessonDetails = lessonDetail;
        //     }
        // );
    }
};
</script>
