<template>
    <ul class="nav">
        <li v-for="list in lists" :key="list.id">
            <a 
                v-if="list.parent_id == null && list.route == null && list.childrens.length <= 0" 
                :href="list.url">
                <span v-html="list.icon"></span> <span>{{ list.title }}</span> 
                <i class="icon-submenu lnr lnr-chevron-left"></i>
            </a>
            <a 
                v-if="list.parent_id == null && list.childrens.length > 0" 
                :href="'#database_'+list.id" 
                data-toggle="collapse" 
                class="collapsed">
                <span v-html="list.icon"></span> <span>{{ list.title }}</span> 
                <i class="icon-submenu lnr lnr-chevron-left"></i>
            </a>
            <router-link 
                v-if="list.route != null" 
                :to="{ name: list.route, params: getParams(list) }" 
                class="nav-link"><span v-html="list.icon"></span> {{ list.title }}</router-link>
            <div 
                :id="'database_'+list.id" 
                class="collapse"
                :class="{show: isActiveRoute(list)}" 
                v-if="list.childrens.length > 0">
                <nestmenu :lists="list.childrens"></nestmenu>
            </div>
        </li>
    </ul>
</template>
<script>
  export default { 
    props: {
        isLoggedIn: Boolean,
        lists: Array,
    },
    name: 'nestmenu',
    data(){
        return {};
    },
    methods: {
        getLink: function(list){
            if (list.childrens.length > 0) {
                return '<a href="#database" data-toggle="collapse" class="collapsed"><i class="fas fa-th-large"></i> <span>'+list.title+'</span><i class="icon-submenu lnr lnr-chevron-left"></i></a>';
            }

            if(list.route != null) {
                return '<router-link :to="{ name: '+list.route+' }" class="nav-link">'+list.title +'</router-link>'
            }
        },
        getParams: function(list) {
            if(list.params != null) {
                if(typeof list.params == 'string') {
                    return JSON.parse(list.params)
                }

                if(Array.isArray(list.params)) {
                    return { ...list.params }
                }

                return list.params
            }

            return {}
        },
        isActiveRoute: function(list) {
            let currentRoute = this.$route.name

            for(let item of list.childrens) {
                if(currentRoute == item.route) {
                    return true
                }
            }
            
            return false
        }
    }
  }
</script>
<style>
</style>