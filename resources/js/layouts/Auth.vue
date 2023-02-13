<template>
    <div class="container top-0 position-sticky z-index-sticky">
        <div class="row">
            <div class="col-12">
                <navbar is-blur="blur border-radius-lg my-3 py-2 start-0 end-0 mx-4 shadow"
                    btn-background="bg-gradient-success" :dark-mode="true" />
            </div>
        </div>
    </div>
    <main class="main-content main-content-bg mt-0">
        <div class="page-header min-vh-100" style="
          background-image: url('https://s3-hcm-r1.longvan.net/19403879-letlearn/images/backgroud.png');
        ">
            <span class="mask bg-gradient-dark opacity-5"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-7 mt-2">
                        <div class="card border-0 mb-0">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
