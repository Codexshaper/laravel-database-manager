<template>
	<div class="row">
	    <div class="col-sm-12 col-md-5">
	        <div class="dataTables_info" id="database-table_info" role="status" aria-live="polite">Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries</div>
	    </div>
	    <div class="col-sm-12 col-md-7">
	        <div class="dataTables_paginate paging_simple_numbers" id="database-table_paginate">
	            <ul class="pagination">
	                <li class="paginate_button page-item previous" :class="{disabled : !pagination.prev_page_url}">
	                    <a 
	                        href="#" 
	                        @click.prevent="change(pagination.prev_page_url)" 
	                        class="page-link">Previous</a>
	                </li>
	                <li 
	                    class="paginate_button page-item" 
	                    :class="{active: page == pagination.current_page}" 
	                    v-for="page in pagination.last_page">
	                    <a 
	                        href="#" 
	                        @click.prevent="change(pagination.path+'?page='+page)" 
	                        class="page-link">{{ page }}</a>
	                </li>
	                <li 
	                    class="paginate_button page-item next" 
	                    :class="{disabled : !pagination.next_page_url}">
	                    <a 
	                        @click.prevent="change(pagination.next_page_url)"
	                        href="#" 
	                        class="page-link">Next</a>
	                </li>
	            </ul>
	        </div>
	    </div>
	</div>
</template>

<script>
	export default {
		props: {
			data: Object,
			change: Function
		},
		computed: {
			pagination: function(){
				return {
				    "current_page"   : this.data.current_page,
				    "last_page"      : this.data.last_page,
				    "first_page_url" : this.data.first_page_url,
				    "prev_page_url"  : this.data.prev_page_url,
				    "next_page_url"  : this.data.next_page_url,
				    "last_page_url"  : this.data.last_page_url,
				    "per_page"       : this.data.per_page,
				    "from"           : this.data.from,
				    "to"             : this.data.to,
				    "total"          : this.data.total,
				    "path"           : this.data.path,
				};
			}
		}
	}
</script>