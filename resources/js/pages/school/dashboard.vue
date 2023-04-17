<template>
    <div class="py-4 container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div v-if="this.data" class="row">
                    <div class="col-lg-3 col-md-6 col-12">
                        <mini-statistics-card
                            title="Total Users"
                            :value="this.data.users.length"
                            description="Total Student, Teachers, and Manager"
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
                            :value="this.data.lessons.length"
                            description="Total Lessons in all courses"
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
                            :value="this.data.courses.length"
                            description="Total Courses in all classes"
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
                            :value="this.data.classes.length"
                            description="Total Classes in school"
                            :icon="{
                                component: 'fa-regular fa-screen-users',
                                background: 'bg-gradient-danger',
                                shape: 'rounded-circle'
                            }"
                        />
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <carousel v-if="this.quotes" :items="this.quotes" style=""/>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body" v-if="this.weather">
                        <div class="row">
                            <div class="col-8 text-start">
                                <h1>{{ Math.round(weather.main.temp) }}&deg;</h1>
                                <h3 class="mb-4">{{ weather.name }} <i
                                    class="fa-sharp fa-solid fa-location-dot fs-4"></i></h3>
                                <p class="mb-0">{{ Math.round(weather.main.temp_min) }}&deg; /
                                    {{ Math.round(weather.main.temp_max) }}&deg; Feels like
                                    {{ Math.round(weather.main.feels_like) }}&deg;</p>
                                <p class="mb-0">{{ new Date().toLocaleString() }}</p>
                            </div>
                            <div class="col-4 text-center">
                                <img :src="'https://openweathermap.org/img/wn/'+ weather.weather[0].icon + '@4x.png'" class="img-fluid"
                                     alt="" srcset="">
                            </div>
                            <div class="col-6 mt-3 text-center">
                                <h5>Sunrise</h5>
                                <p class="mb-1"><i class="fa-sharp fa-solid fa-sunrise fs-1 text-warning"></i></p>
                                Sunrise: {{ new Date(weather.sys.sunrise * 1000).toLocaleTimeString() }}
                            </div>
                            <div class="col-6 mt-3 text-center">
                                <h5>Sunset</h5>
                                <p class="mb-1"><i class="fa-sharp fa-solid fa-sunset fs-1 text-warning"></i></p>
                                Sunset: {{ new Date(weather.sys.sunset * 1000).toLocaleTimeString() }}
                            </div>
                        </div>
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
            slug: this.$route.params.slug,
            quotes: [],
            weather: null,
        };
    },
    title() {
        if (this.data) {
            return this.data.name + ' Dashboard';
        } else {
            return 'Dashboard';
        }
    },
    created() {
        this.unsubscribe = this.$store.subscribe((mutation) => {
            if (mutation.type === 'schoolDashboard/request') {
            } else if (mutation.type === 'schoolDashboard/success') {
                this.data = mutation.payload;
            } else if (mutation.type === 'schoolDashboard/failure') {
                this.$root.showSnackbar('Load dashboard failed !', 'danger');
            }
        });
        this.$store.dispatch('schoolDashboard/getInfo', this.$route.params.slug);
        this.getQuote();
        this.getWeather();
    },
    beforeUnmount() {
        this.unsubscribe();
    },
    methods: {
        getQuote() {
            fetch('https://api.quotable.io/quotes/random?limit=5&minLength=150')
                .then(response => response.json())
                .then(data => {
                    this.quotes = data.map((item) => {
                        return {
                            img: 'https://source.unsplash.com/800x600/?nature,water',
                            title: item.author,
                            description: item.content,
                            icon: {
                                component: 'ni ni-camera-compact text-dark',
                                background: 'bg-white'
                            }
                        }
                    })
                })
        },
        getWeather() {
            // get current location
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    let lat = position.coords.latitude;
                    let lon = position.coords.longitude;
                    let url = 'https://api.openweathermap.org/data/2.5/weather?lat=' + lat + '&lon=' + lon + '&appid=ceb13399bbf6de4efe3474a720d6252c&units=metric';
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            this.weather = data;
                        })
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    },
}
</script>
