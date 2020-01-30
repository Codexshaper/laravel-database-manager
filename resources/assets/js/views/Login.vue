<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div 
                  class="database-error alert alert-danger" 
                  role="alert" 
                  v-for="(databaseError,key) in databaseErrors" 
                  :key="key"> {{ databaseError }} </div>
                <transition name="fade" mode="out-in">
                    <div class="card card-default longin-card">
                        <div class="card-header login-header">Login</div>
                        <div class="card-body longin-body">
                            <form>
                                <!-- Stop Auto fill password -->
                                <input type="password" style="display:none">
                                
                                <div class="form-group row">
                                    <label for="email" class="col-sm-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" v-model="email" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" v-model="password" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-info" @click="handleSubmit">
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: "",
                password: "",
                prefix: localStorage.getItem('dbm.prefix')
            }
        },
        created(){
            this.redirectTo()
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault()
                this.validation();
                if (this.password.length > 0) {
                    axios.post('/api'+this.prefix+'/login',{
                      data: {
                        email: this.email,
                        password: this.password
                      }
                    })
                    .then(res => {
                        if(res.data.success == true) {
                            localStorage.setItem('dbm.user', JSON.stringify(res.data.user))
                            localStorage.setItem('dbm.authToken', res.data.token)
                            let now = new Date()
                            let expiry = now.getTime() + res.data.expiry
                            localStorage.setItem('dbm.authTokenExpiry', expiry)
                            this.redirectTo()
                        }
                    })
                    .catch(err => {
                        this.displayError(err.response)
                    });
                }
            },
            redirectTo: function(){
                if (localStorage.getItem('dbm.authToken') != null) {
                    this.$emit('check')
                    if (this.$route.params.nextUrl != null) {
                        this.$router.push(this.$route.params.nextUrl)
                    } else {
                        this.$router.push({name: 'database'})
                    }
                }
            },
            validation(){
                this.databaseErrors = [];
                    
                if(this.email == "" || this.email == undefined) {
                    this.databaseErrors.push("Email required");
                }

                if(this.password == "" || this.password == undefined) {
                    this.databaseErrors.push("Password required");
                }
            } 
        }
    }
</script>
