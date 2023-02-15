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
                  }" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Sets" value="2,300" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+3%</span> since last week" :icon="{
                      component: 'fa-sharp fa-solid fa-books',
                      background: 'bg-gradient-danger',
                      shape: 'rounded-circle'
                  }" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Folders" value="+3,462" description="<span
                  class='text-sm font-weight-bolder text-danger'
                  >-2%</span> since last quarter" :icon="{
                      component: 'fa-solid fa-folders',
                      background: 'bg-gradient-success',
                      shape: 'rounded-circle'
                  }" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card title="Total Classes" value="$103,430" description="<span
                  class='text-sm font-weight-bolder text-success'
                  >+5%</span> than last month" :icon="{
                      component: 'fa-duotone fa-screen-users',
                      background: 'bg-gradient-warning',
                      shape: 'rounded-circle'
                  }" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7 mb-lg">
                        <gradient-line-chart id="chart-line" title="Sales Overview" description="<i class='fa fa-arrow-up text-success'></i>
        <span class='font-weight-bold'>4% more</span> in 2021" :chart="{
            labels: [
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            datasets: [
                {
                    label: 'Mobile Apps',
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500]
                }
            ]
        }" />
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
                        ]" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import MiniStatisticsCard from "@/components/Cards/MiniStatisticsCard.vue";
import GradientLineChart from "@/components/GradientLineChart.vue";
import Carousel from "@/components/Carousel.vue";
import { mapActions , mapState} from "vuex";
export default {
    name: "AdminDashboard",
    components: {
        MiniStatisticsCard,
        GradientLineChart,
        Carousel,
    },
    data() {
        return {
        };
    },
    created() {
        this.getQuotes();
    },
    methods: {
        ...mapActions('dashboard', ['getQuotes']),
    },
    computed: {
        ...mapState({
            quotes: state => state.dashboard.quotes,
        }),
    },
};
</script>
