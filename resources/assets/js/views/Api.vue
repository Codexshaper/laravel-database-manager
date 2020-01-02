<template>
    <transition name="fade" mode="out-in">
        <div class="vue-content">
            <clients></clients>
            <authorized-clients></authorized-clients>
            <personal-access-tokens></personal-access-tokens>
        </div>
    </transition>
</template>

<script>
    import Clients from '../components/passport/Clients.vue'
    import AuthorizedClients from '../components/passport/AuthorizedClients.vue'
    import PersonalAccessTokens from '../components/passport/PersonalAccessTokens.vue'

    export default {
        components: {
            Clients,
            AuthorizedClients,
            PersonalAccessTokens
        },
        created(){
            console.log("Api called")
        }
    }
</script>