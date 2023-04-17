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
                    :class="getRoute() === 'dashboard' ? 'active' : ''"
                    @click="go('admin.dashboard')"
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
                    :class="getRoute() === 'setting' ? 'active' : ''"
                    @click="go('admin.setting')"
                >
                    <template #icon>
                        <i class="fa-regular fa-gear text-info text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="setting"
                    nav-text="Notification"
                    :class="getRoute() === 'notification' ? 'active' : ''"
                    @click="go('admin.notification')"
                >
                    <template #icon>
                        <i class="fa-regular fa-bell text-success text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    USER
                </h6>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="user"
                    nav-text="User"
                    :class="getRoute() === 'user' ? 'active' : ''"
                    @click="go('admin.user')"
                >
                    <template #icon>
                        <i class="fa-regular fa-user text-warning text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="role"
                    nav-text="Role"
                    :class="getRoute() === 'role' ? 'active' : ''"
                    @click="go('admin.role')"
                >
                    <template #icon>
                        <i class="fa-light fa-users text-danger text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="mt-3 nav-item">
                <h6 class="text-xs ps-4 text-uppercase font-weight-bolder opacity-6 ms-2">
                    LEARN
                </h6>
            </li>
            <li class="nav-item">
                <sidenav-collapse collapse-ref="dashboardsExamples" nav-text="School"
                                  :class="getRoute() === 'dashboards' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-school text-primary text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <!-- nav links -->
                            <sidenav-item :to="{ name: 'admin.school.index' }" mini-icon="M" text="Manage"/>
                            <sidenav-item :to="{ name: 'admin.role' }" mini-icon="R" text="Request"/>
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse collapse-ref="dashboardsExamples" nav-text="Lesson"
                                  :class="getRoute() === 'lesson' ? 'active' : ''">
                    <template #icon>
                        <i class="fa-regular fa-books text-info text-sm opacity-10"></i>
                    </template>
                    <template #list>
                        <ul class="nav ms-4">
                            <!-- nav links -->
                            <sidenav-item :to="{ name: 'admin.lesson.index' }" mini-icon="M" text="Manage"/>
                            <sidenav-item :to="{ name: 'admin.lesson.add' }" mini-icon="A" text="Add"/>
                        </ul>
                    </template>
                </sidenav-collapse>
            </li>
            <li class="nav-item">
                <sidenav-collapse
                    collapse-ref="course"
                    nav-text="Course"
                    :class="getRoute() === 'course' ? 'active' : ''"
                    @click="go('admin.course')"
                >
                    <template #icon>
                        <i class="fa-regular fa-folder text-success text-sm opacity-10"></i>
                    </template>
                </sidenav-collapse>
            </li>
        </ul>
    </div>
</template>
<script>
import SidenavItem from "../../../components/sidenavs/sidenav-item.vue";
import SidenavCollapse from "../../../components/sidenavs/sidenav-collapse.vue";
import SidenavCollapseItem from "../../../components/sidenavs/sidenav-collapse-item.vue";

export default {
    name: "SidenavList",
    components: {
        SidenavItem,
        SidenavCollapse,
        SidenavCollapseItem
    },
    methods: {
        getRoute() {
            const routeArr = this.$route.path.split("/");
            return routeArr[2] ? routeArr[2] : routeArr[1];
        },
        go(name) {
            this.$router.push({name: name});
        }
    }
};
</script>
