<template>
    <div class="container">
        <h2 class="pt-3">{{ title }}</h2>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="submitted-tab" data-bs-toggle="tab" data-bs-target="#submitted" type="button" role="tab" aria-controls="submitted" aria-selected="true">
                    {{ submittedCount }} submitted
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unsubmitted-tab" data-bs-toggle="tab" data-bs-target="#unsubmitted" type="button" role="tab" aria-controls="unsubmitted" aria-selected="false">
                    {{ unsubmittedCount }} unsubmitted
                </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="submitted" role="tabpanel" aria-labelledby="submitted-tab">
                <div class="row pt-4">
                    <div v-for="(item, index) in data.submitted" :key="index" class="col-sm-6 col-md-4 col-lg-3 mb-3">
                        <div class="card d-flex align-items-center pt-2">
                            <div class="col-4">
                                <img :src="item.avatar" class="rounded-circle me-3" alt="..." style="width: 100px; height: 100px;">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-0">
                                    <h5 class="card-title my-2 justify-content-center text-center">{{ item.name }}</h5>
                                    <p v-if="item.description" class="card-text">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="unsubmitted" role="tabpanel" aria-labelledby="unsubmitted-tab">
                <div class="row pt-4">
                    <div v-for="(item, index) in data.unsubmitted" :key="index" class="col-sm-6 col-md-4 col-lg-3 mb-3">
                        <div class="card d-flex align-items-center pt-2">
                            <div class="col-4">
                                <img :src="item.avatar" class="rounded-circle me-3" alt="..." style="width: 100px; height: 100px;">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-0">
                                    <h5 class="card-title my-2 justify-content-center text-center">{{ item.name }}</h5>
                                    <p v-if="item.description" class="card-text">{{ item.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            title: "HCM test 1",
            data: {
                submitted: [
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Vu Hai Long"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Dao Xuan Dat" },
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Tuan"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Tuan Anh"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Vu Hong Phuc"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Manh Dat"},
                ],
                unsubmitted: [
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Hoa"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Le Quynh Trang" },
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Thu"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Huong"},
                    { avatar: "https://cdn.discordapp.com/attachments/702150671943860266/1088118611794726922/ArsBlink.JPG", name: "Vy"},
                ]
            },
        };
    },
    computed: {
        submittedCount() {
            return this.data.submitted.length;
        },
        unsubmittedCount() {
            return this.data.unsubmitted.length;
        }
    }
};
</script>
