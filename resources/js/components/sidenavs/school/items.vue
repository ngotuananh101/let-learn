<template>
    <div
        id="sidenav-collapse-main"
        class="collapse navbar-collapse w-auto h-auto h-100 overflow-hidden"
    >
        <ul class="navbar-nav">
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="dashboard"
                    nav-text="Dashboard"
                    :class="getRoute() === 'school.dashboard' ? 'active' : ''"
                    @click="go('school.dashboard')"
                >
                    <template #icon>
                        <i class="fa-regular fa-chart-line text-primary text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="setting"
                    nav-text="Setting"
                    :class="getRoute() === 'school.setting' ? 'active' : ''"
                    @click="go('school.setting')"
                >
                    <template #icon>
                        <i class="fa-regular fa-gear text-info text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="user"
                    nav-text="User"
                    :class="getRoute() === 'school.users' ? 'active' : ''"
                    @click="go('school.users')"
                >
                    <template #icon>
                        <i class="fa-regular fa-user text-success text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse collapse-ref="dashboardsExamples" nav-text="Lesson"
                                  :class="getRoute() === 'lesson' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-books text-warning text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <!-- nav links -->
                            <sidenav-item :to="{ name: 'school.lesson.index' }" mini-icon="M" text="Manage"/>
                            <sidenav-item :to="{ name: 'school.lesson.add' }" mini-icon="A" text="Add"/>
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="course"
                    nav-text="Course"
                    :class="getRoute() === 'course' ? 'active' : ''"
                    @click="go('school.course')"
                >
                    <template #icon>
                        <i class="fa-regular fa-folder text-danger text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse collapse-ref="classManager" nav-text="Class"
                                  :class="getRoute() === 'class' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-screen-users text-info text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <!-- nav links -->
                            <sidenav-item :to="{ name: 'school.class.index' }" mini-icon="I" text="Index"/>
<!--                            <sidenav-item :to="{ name: 'school.class.add' }" mini-icon="A" text="Add"/>-->
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
        </ul>
    </div>
</template>

<script>
import SidenavCollapse from "../../../components/sidenavs/sidenav-collapse.vue";
import SidenavItem from "../sidenav-item.vue";
export default {
    name: "items",
    components: {
        SidenavItem,
        SidenavCollapse,
    },
    methods: {
        getRoute() {
            return this.$route.name;
        },
        go(name) {
            this.$router.push({name: name});
        }
    }
}
</script>

<style scoped>

</style>
