<template>
	<!-- LEFT SIDEBAR -->
	<div id="sidebar-nav" class="sidebar">
	    <div class="logo">
	        <a href="#"><img src="#" alt="" class="img-responsive"></a>
	    </div>
	    <div class="sidebar-scroll">
	        <nav>
	            <ul class="nav">
	                <li>
	                    <a href="#database" data-toggle="collapse" class="collapsed"><i class="fas fa-th-large"></i> <span>Database</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
	                    <div id="database" class="collapse" :class="{show: isActiveMenu}">
	                        <ul class="nav database-menu">
	                        	<li><router-link :to="{ name: 'database' }" class="nav-link" v-if="isLoggedIn">Table</router-link></li>
	                        	<li><router-link :to="{ name: 'crud' }" class="nav-link" v-if="isLoggedIn">Crud</router-link></li>
	                        	<li><router-link :to="{ name: 'permission' }" class="nav-link" v-if="isLoggedIn">Permission</router-link></li>
	                        	<li><router-link :to="{ name: 'backup' }" class="nav-link" v-if="isLoggedIn">Backup</router-link></li>
	                        </ul>
	                    </div>
	                </li>
	            </ul>
	        </nav>
	    </div>
	</div>
	<!-- END LEFT SIDEBAR -->
</template>

<script>
	export default {
		props: {
			isLoggedIn: Boolean
		},
		computed: {
			isActiveMenu: function(){
				switch(this.$route.name) {
					case 'database':
					case 'crud':
					case 'permission':
					case 'backup':
						return true;
					break;
				}
				
				return false;
			}
		}
	}
</script>

<style>
	.database-menu li a.router-link-exact-active{
		color: #fff;
	}
</style>