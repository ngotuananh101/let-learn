<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div v-if="this.data" class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Users"
                            :value="this.data.analytics.local.users.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${this.data.analytics.local.users.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-users',
                                background: 'bg-gradient-primary',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Lessons"
                            :value="this.data.analytics.local.lessons.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${this.data.analytics.local.lessons.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-books',
                                background: 'bg-gradient-success',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Courses"
                            :value="this.data.analytics.local.courses.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${this.data.analytics.local.courses.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-folder-open',
                                background: 'bg-gradient-warning',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Classes"
                            :value="this.data.analytics.local.classes.total"
                            :description="`<span class='text-sm font-weight-bolder text-success'>
                                            ${this.data.analytics.local.classes.change}</span> since yesterday`"
                            :icon="{
                                component: 'fa-regular fa-screen-users',
                                background: 'bg-gradient-danger',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                </div>
                <div class="row">
                    <div v-if="this.data" class="col-lg-7 mb-lg">
                        <gradient-line-chart id="chart-line"
                                             title="Average Duration" description="<i class='fa-solid fa-timer text-success'></i>
        <span class='font-weight-bold'>average session duration</span> of last 30 days" :chart="{
            labels: this.data.analytics.duration.date,
            datasets: [
                {
                    label: 'Duration (s)',
                    data: this.data.analytics.duration.duration,
                }
            ]
        }"/>
                    </div>
                    <div v-if="this.data" class="col-lg-5">
                        <carousel :items="this.data.quotes"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <stats-chart
                            v-if="this.data"
                            :title="'This week vs Last week'"
                            :subtitle="'By Page views'"
                            :badge="['This week', 'Last week']"
                            :id="'page-views-week'"
                            :labels="this.weekday"
                            :datasets="[
                                                {
                                                    label: 'This week',
                                                    pointBackgroundColor: 'white',
                                                    borderColor: '#2dce89',
                                                    data: this.data.analytics.week.lastweek,
                                                },
                                                {
                                                    label: 'Last week',
                                                    pointBackgroundColor: 'white',
                                                    borderColor: '#f5365c',
                                                    data: this.data.analytics.week.thisweek,
                                                }
                                            ]"
                        />
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <country-and-browser
                            v-if="this.data"
                            id="top-browsers"
                            title="Top Browsers"
                            subtitle="by sessions"
                            description="This is top browsers by sessions"
                            type="browsers"
                            :chart="{ data: this.data.analytics.sessions.browser }"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <stats-chart
                            v-if="this.data"
                            :title="'This week vs Last week'"
                            :subtitle="'By Events'"
                            :badge="['This week', 'Last week']"
                            :id="'page-views-week-by-event'"
                            :labels="this.weekday"
                            :datasets="[
                                                {
                                                    label: 'This week',
                                                    pointBackgroundColor: 'white',
                                                    borderColor: '#2dce89',
                                                    data: this.data.analytics.weekByEvent.lastweek,
                                                },
                                                {
                                                    label: 'Last week',
                                                    pointBackgroundColor: 'white',
                                                    borderColor: '#f5365c',
                                                    data: this.data.analytics.weekByEvent.thisweek,
                                                }
                                            ]"
                        />
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <country-and-browser
                            v-if="this.data"
                            id="top-countries"
                            title="Top Countries"
                            subtitle="by sessions"
                            description="This is top countries by sessions"
                            type="countries"
                            :chart="{ data: this.data.analytics.sessions.country }"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import miniStatisticsCard from "../../components/cards/mini-statistics-card.vue";
import gradientLineChart from "../../components/charts/gradient-line-chart.vue";
import statsChart from "../../components/charts/stats-chart.vue";
import countryAndBrowser from "../../components/charts/country-and-browser.vue";
import carousel from "../../components/cards/carousel.vue";
import overlay from "../../helpers/other/loader";

export default {
    name: "Admin Dashboard",
    components: {
        miniStatisticsCard,
        gradientLineChart,
        statsChart,
        countryAndBrowser,
        carousel,
    },
    data() {
        return {
            unsubscribe: null,
            data: null,
            weekday: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        };
    },
    title() {
        return 'Admin Dashboard' + ' - ' + document.querySelector('meta[name="title"]').getAttribute('content');
    },
    beforeMount() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'adminDashboard/request') {
                overlay();
                this.$root.showSnackbar('Loading dashboard...', 'info');
            } else if (mutation.type === 'adminDashboard/success') {
                overlay();
                this.$root.showSnackbar('Load dashboard successfully !', 'success');
                this.data = mutation.payload;
                this.setQuote();
                this.setDuration();
                this.setWeek();
                this.setWeekByEvent();
            } else if (mutation.type === 'adminDashboard/failure') {
                overlay();
                this.$root.showSnackbar('Load dashboard failed !', 'danger');
            }
        });
        this.$store.dispatch('adminDashboard/get');
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        setQuote() {
            this.data.quotes = this.data.quotes.map((item) => {
                return {
                    img: 'https://source.unsplash.com/800x600/?nature,water',
                    title: item.author,
                    description: item.content,
                    icon: {
                        component: 'ni ni-camera-compact text-dark',
                        background: 'bg-white'
                    }
                }
            });
        },
        setDuration(){
            let date = [];
            let duration = [];
            this.data.analytics.duration.forEach((item) => {
                date.push(`${item.month}-${item.day}`);
                duration.push(parseFloat(item.averageSessionDuration).toFixed(2));
            });
            this.data.analytics.duration = {
                date: date,
                duration: duration,
            };
        },
        setWeek(){
            let last_week = this.weekday.reduce((acc, day, index) => {
                const item = this.data.analytics.week.lastWeek.find(item => item.dayOfWeek === `${index}`);
                acc.push(item ? item.screenPageViews : 0);
                return acc;
            }, []);
            // get this week data
            let this_week = this.weekday.reduce((acc, day, index) => {
                const item = this.data.analytics.week.thisWeek.find(item => item.dayOfWeek === `${index}`);
                acc.push(item ? item.screenPageViews : 0);
                return acc;
            }, []);
            this.data.analytics.week = {lastweek: last_week, thisweek: this_week};
        },
        setWeekByEvent(){
            let last_week = this.weekday.reduce((acc, day, index) => {
                const item = this.data.analytics.weekByEvent.lastWeek.find(item => item.dayOfWeek === `${index}`);
                acc.push(item ? item.eventCount : 0);
                return acc;
            }, []);
            // get this week data
            let this_week = this.weekday.reduce((acc, day, index) => {
                const item = this.data.analytics.weekByEvent.thisWeek.find(item => item.dayOfWeek === `${index}`);
                acc.push(item ? item.eventCount : 0);
                return acc;
            }, []);
            this.data.analytics.weekByEvent = {lastweek: last_week, thisweek: this_week};
        },
    },
}
</script>
