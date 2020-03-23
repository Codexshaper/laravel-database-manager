<template>
    <div>
        <left-sidebar :menus="menus" :isLoggedIn="isLoggedIn"></left-sidebar>
        <div class="main">
            <!-- MAIN CONTENT -->
            <!-- NAVBAR -->
            <nav class="navbar navbar-default navbar-fixed-top">
                <div class="navbar-btn">
                <button type="button" class="btn-toggle-fullwidth">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
                <div id="navbar-menu">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span>Codexshapper</span> <i class="icon-submenu lnr lnr-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="#"><i class="lnr lnr-user"></i> <span>My Profile</span></a></li>
                                <li><a href="#"><i class="lnr lnr-envelope"></i> <span>Message</span></a></li>
                                <li><a href="#"><i class="lnr lnr-cog"></i> <span>Settings</span></a></li>
                                <li><a href="#" v-if="isLoggedIn" @click.prevent="logout"><i class="lnr lnr-exit"></i> <span>Logout</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- END NAVBAR -->
            <div class="main-content">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item" v-for="(breadcrumb,index) in $route.meta.breadcrumbs" :key="index">
                        <router-link 
                            :to="{ name: breadcrumb.name }"
                            :class="'crumb-link'" 
                            v-if="isLoggedIn">{{ breadcrumb.display }}</router-link>
                    </li>
                  </ol>
                </nav>
                <router-view 
                    :key="$route.fullPath" 
                    :prefix="prefix" 
                    :driver="driver" 
                    @check="check"></router-view>
                <!-- set progressbar -->
                <vue-progress-bar></vue-progress-bar>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
       
    </div>
</template>

<script>

    // Import vue components
    import LeftSidebar from './partials/LeftSidebar.vue';
    import Database from '../views/Database.vue'
    
    // Set Default functionality for all vue components
    export default {

        props: ["driver"],
        components:{
            LeftSidebar
        },
        data() {
            return {
                menus: null,
                name: null,
                user_type: 0,
                basePath: localStorage.getItem('dbm.basePath'),
                prefix: localStorage.getItem('dbm.prefix'),
                isLoggedIn: localStorage.getItem('dbm.authToken') != null
            }
        },
        created(){
            localStorage.setItem('dbm.prefix', '');
            if(localStorage.getItem('dbm.prefix') != null && localStorage.getItem('dbm.prefix') != this.prefix) {
                localStorage.removeItem('dbm.prefix');
                localStorage.setItem('dbm.prefix', this.prefix);
            }
            // Set Base URL for axios request
            axios.defaults.baseURL = this.basePath;

            //  [App.vue specific] When App.vue is first loaded start the progress bar
            this.$Progress.start()
            //  hook the progress bar to start before we move router-view
            this.$router.beforeEach((to, from, next) => {
              //  does the page we want to go to have a meta.progress object
              if (to.meta.progress !== undefined) {
                let meta = to.meta.progress
                // parse meta tags
                this.$Progress.parseMeta(meta)
              }
              //  start the progress bar
              this.$Progress.start()
              //  continue to next page
              next()
            })
            //  hook the progress bar to finish after we've finished moving router-view
            this.$router.afterEach((to, from) => {
              //  finish the progress bar
              this.$Progress.finish()
            })
        },
        mounted() {
            this.getMenus();
            this.setDefaults();
            this.$Progress.finish()
        },
        methods : {
            getMenus: function() {
                axios.get('/api'+this.prefix+'/menus')
                .then(res => {
                    if( res.data.success == true ){
                        this.menus = res.data.menus;
                    } 
                })
                .catch(err => this.displayError(err.response));
            },
            setDefaults() {
                if (this.isLoggedIn) {
                    let user = JSON.parse(localStorage.getItem('dbm.user'))
                    this.name = user.name
                }
            },
            check(type = 'authenticate') {
                switch(type) {
                    case 'authenticate':
                        this.isLoggedIn = localStorage.getItem('dbm.authToken') != null
                        this.setDefaults()
                        break
                    case 'load':
                        this.isLoaded = false;
                        this.loadComponent();
                        break
                    case 'reloadMenu':
                        this.getMenus();
                        break;
                }
            },
            logout(){
                localStorage.removeItem('dbm.authToken')
                localStorage.removeItem('dbm.user')
                this.check()
                this.$router.push({name: 'login'})
            }
        }
    }
</script>

<style>
    .loading {
        position: absolute;
        top: 0px;
        left: 0px;
        z-index: 99999;
        width: 100%;
        height: 100vh;
        background-color: rgba(255,255,255);
        color: red;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #ffffff;
        transition: 0.5s;
    }

    .fade-enter {
        transform: translateY(10px);
        opacity: 0;
    }
    .fade-enter-to {
        transform: translateX(0px);
    }
    .fade-enter-active {
        transition: all 0.3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .fade-leave-active, .fade-leave-to {
        opacity: 0;
    }
</style>