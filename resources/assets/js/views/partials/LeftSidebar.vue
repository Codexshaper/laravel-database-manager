<template>
	<!-- LEFT SIDEBAR -->
	<div id="sidebar-nav" class="sidebar">
	    <div class="logo">
	        <a href="#"><img src="#" alt="" class="img-responsive"></a>
	    </div>
	    <div class="sidebar-scroll">
	        <nav>
	            <ul class="nav">
	                <li class="menu-title"> menus   </li>
	                <li><a href="#" class="active"><i class="fas fa-desktop"></i><span>Dashboard</span></a></li>
	                <li><router-link :to="{ name: 'api' }" class="nav-link"><i class="fas fa-desktop"></i> API</router-link></li>
	                <li>
	                    <a href="#database" data-toggle="collapse" class="collapsed"><i class="fas fa-th-large"></i> <span>Database</span> <i class="icon-submenu lnr lnr-chevron-left"></i></a>
	                    <div id="database" class="collapse ">
	                        <ul class="nav">
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
		}
	}
</script>