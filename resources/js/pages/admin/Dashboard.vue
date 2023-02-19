<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Users" value="$53,000" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+55%</span> since yesterday" :icon="{
                      component: 'fa-regular fa-users',
                      background: 'bg-gradient-primary',
                      shape: 'rounded-circle'
                  }"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Sets" value="2,300" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+3%</span> since last week" :icon="{
                      component: 'fa-sharp fa-solid fa-books',
                      background: 'bg-gradient-danger',
                      shape: 'rounded-circle'
                  }"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Folders" value="+3,462" description="<span
                  class='text-sm font-weight-bolder text-danger'
                  >-2%</span> since last quarter" :icon="{
                      component: 'fa-solid fa-folders',
                      background: 'bg-gradient-success',
                      shape: 'rounded-circle'
                  }"/>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Classes" value="$103,430" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+5%</span> than last month" :icon="{
                      component: 'fa-duotone fa-screen-users',
                      background: 'bg-gradient-warning',
                      shape: 'rounded-circle'
                  }"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 mb-lg">
                        <gradient-line-chart v-if="this.analytics.averageSessionDuration" id="chart-line"
                                             title="Average Duration" description="<i class='fa-solid fa-timer text-success'></i>
        <span class='font-weight-bold'>average session duration</span> of last 30 days" :chart="{
            labels: this.analytics.averageSessionDuration.date,
            datasets: [
                {
                    label: 'Duration (min)',
                    data: this.analytics.averageSessionDuration.value,
                }
            ]
        }"/>
                    </div>
                    <div class="col-lg-5">
                        <carousel v-if="quotes" :items="[
                            {
                                img: 'https://source.unsplash.com/1600x900/?nature',
                                title: `“${quotes[0].quoteAuthor}”`,
                                description: `“${quotes[0].quoteText}”`,
                                icon: {
                                    component: 'ni ni-camera-compact text-dark',
                                    background: 'bg-white'
                                }
                            },
                            {
                                img: 'https://source.unsplash.com/1600x900/?travel',
                                title: `“${quotes[1].quoteAuthor}”`,
                                description: `“${quotes[1].quoteText}”`,
                                icon: {
                                    component: 'ni ni-bulb-61 text-dark',
                                    background: 'bg-white'
                                }
                            },
                            {
                                img: 'https://source.unsplash.com/1600x900/?street-photography',
                                title: `“${quotes[2].quoteAuthor}”`,
                                description: `“${quotes[2].quoteText}”`,
                                icon: {
                                    component: 'ni ni-trophy text-dark',
                                    background: 'bg-white'
                                }
                            }
                        ]"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <div class="card">
                            <div class="p-3 pb-0 card-header">
                                <h6 class="mb-0">This Week vs Last Week</h6>
                                <span class="fs-6">by page views</span>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-success"></i>
                                        <span class="text-xs text-dark">This Week</span>
                                    </span>
                                    <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-danger"></i>
                                        <span class="text-xs text-dark">Last Week</span>
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 card-body">
                                <div class="chart">
                                    <!-- <StatsChart id="page-views-week" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <default-doughnut-chart
                            v-if="this.analytics.browser"
                            id="top-browsers"
                            title="Top Browsers"
                            subtitle="by sessions"
                            description="This is top browsers by sessions"
                            type="browsers"
                            :chart="{ data: this.analytics.browser }"/>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 col-md-12">
                        <div class="card">
                            <div class="p-3 pb-0 card-header">
                                <h6 class="mb-0">This Week vs Last Week</h6>
                                <span class="fs-6">by page views</span>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-success"></i>
                                        <span class="text-xs text-dark">This Week</span>
                                    </span>
                                    <span class="badge badge-md badge-dot me-4">
                                        <i class="bg-danger"></i>
                                        <span class="text-xs text-dark">Last Week</span>
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 card-body">
                                <div class="chart">
                                    <!-- <StatsChart id="page-views-week" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 col-lg-5 col-md-12 mt-lg-0">
                        <default-doughnut-chart
                            v-if="this.analytics.country"
                            id="top-countries"
                            title="Top Countries"
                            subtitle="by sessions"
                            description="This is top countries by sessions"
                            type="countries"
                            :chart="{ data: this.analytics.country }"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MiniStatisticsCard from "@/components/Cards/MiniStatisticsCard.vue";
import GradientLineChart from "@/components/Charts/GradientLineChart.vue";
import Carousel from "@/components/Carousel.vue";
import StatsChart from "@/components/Charts/StatsChart.vue";
import DefaultDoughnutChart from "@/components/Charts/DefaultDoughnutChart.vue";
import {mapActions, mapState} from "vuex";

export default {
    name: "AdminDashboard",
    components: {
        MiniStatisticsCard,
        GradientLineChart,
        Carousel,
        StatsChart,
        DefaultDoughnutChart,
    },
    data() {
        return {};
    },
    created() {
        this.getQuotes();
        this.getAnalytics();
    },
    methods: {
        ...mapActions('dashboard', ['getQuotes', 'getAnalytics']),
    },
    computed: {
        ...mapState({
            quotes: state => state.dashboard.quotes,
            analytics: state => state.dashboard.analytics,
        }),
    },
};
</script>
