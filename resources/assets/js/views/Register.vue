<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <transition name="fade" mode="out-in">
                    <div class="card card-default">
                        <div class="card-header">Register</div>
                        <div class="card-body">
                            <form>
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" v-model="name" required autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" v-model="email" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" v-model="password" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" v-model="password_confirmation" required>
                                    </div>
                                </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary" @click="handleSubmit">
                                            Register
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
    props : ['nextUrl'],
    data(){
        return {
            name : "",
            email : "",
            password : "",
            password_confirmation : ""
        }
    },
    methods : {
        handleSubmit(e) {
            e.preventDefault()
            if (this.password !== this.password_confirmation || this.password.length <= 0) {
                this.password = ""
                this.password_confirmation = ""
                return alert('Passwords do not match')
            }
            let name = this.name
            let email = this.email
            let password = this.password
            let c_password = this.password_confirmation
            axios.post('api/register', {name, email, password, c_password}).then(response => {
                let data = response.data
                localStorage.setItem('bigStore.user', JSON.stringify(data.user))
                localStorage.setItem('bigStore.jwt', data.token)
                if (localStorage.getItem('bigStore.jwt') != null) {
                    this.$emit('loggedIn')
                    let nextUrl = this.$route.params.nextUrl
                    this.$router.push((nextUrl != null ? nextUrl : '/'))
                }
            })
        }
    }
}
</script>
