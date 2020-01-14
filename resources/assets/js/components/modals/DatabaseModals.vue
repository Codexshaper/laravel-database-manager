<template>
	<div class="row">
	 <div class="col-sm-12">
	 	<!-- View -->
		<div class="modal fade" id="viewDatabaseModal">
            <div class="modal-dialog view-modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                      	<h4 class="modal-title font-weight-bold">{{ category }}</h4>
                      	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       	 <span aria-hidden="true">&times;</span>
                      	</button>
                    </div>
                    <div class="modal-body">	

                    	<div class="table-responsive view-modal-content">
	                    	<table class="table table-bordered">
		                      	<thead class="thead-light">
		                      	 	<tr>
		                      	 	 	<th>Field Name</th>
		                      	 	 	<th>Type</th>
		                      	 	 	<th>Length</th>
		                      	 	 	<th>Null</th>
		                      	 	 	<th>Index</th>
		                      	 	 	<th>Auto_Increment</th>
		                      	 	 	<th>Default</th>
		                      	 	</tr>
		                      	</thead>
		                      	<tbody>
		                      		<tr v-for="(field,index) in fields" :key="index">
		                      			<td>{{ field.name }}</td>
		                      			<td>{{ field.type.name }}</td>
		                      			<td>{{ field.length }}</td>
		                      			<td>{{ field.notnull ? 'Yes' : 'No' }}</td>
		                      			<td>{{ field.index }}</td>
		                      			<td>{{ field.autoincrement ? 'Yes' : 'No' }}</td>
		                      			<td>{{ field.default }}</td>
		                      		</tr>

		                      	</tbody>
		                    </table>
                    	</div>                  
	                     <div class="modal-footer text-right">
	                     	<button type="button" class="btn btn-danger cs-all-btn" data-dismiss="modal">Close</button>
	                     </div>
                    </div>
               </div>
            </div>
        </div>
	    <!--  create form modal -->
	    <div 
	    	v-if="hasPermission('database.create')" 
	    	class="modal fade" 
	    	id="createTableModal" 
	    	tabindex="-1" 
	    	role="dialog" 
	    	aria-labelledby="createTableModal"
	        aria-hidden="true">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	              <div class="modal-header">
	                  <h4 class="modal-title font-weight-bold">Create Table</h4>
	                  <button type="button" style="color:#fff" class="close" data-dismiss="modal" aria-label="Close">
	                      <span aria-hidden="true">&times;</span>
	                  </button>
	              </div>
	              <div class="modal-body">
	                  <form action="" v-on:submit.prevent="createTable(table)">
	                      <div class="form-group cs-form-group">
	                          <label class="cs-label">Name</label>
	                          <input type="text" name="name" v-model="table.name" class="form-control">
	                      </div>
	                      <div class="form-group cs-form-group row">
	                          <label class="col-md-12">Collation</label>
	                          <div class="col-md-12">
	                          	<component 
                                  :is="loadTypeCompnent"
                                  :table="table"></component>
	                          </div>
	                      </div>
	                      <div class="m-footer text-right">
	                          <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
	                          <button type="submit" class="btn btn-info ">Add</button>
	                      </div>
	                  </form>
	              </div>
	            </div>
	        </div>
	    </div>

	  </div>
	</div>
</template>
<script>
	import Mysql from '../../components/collations/Mysql.vue';

	export default {
		props: {
			category: String,
			tableName: String,
			collation: String,
			charset: String,
			fields: Array,
			createTable: Function,
			userPermissions: Array,
			connection: String,
		},
		data(){
			return {
				table: {
					name: this.tableName,
					collation: this.collation,
					charset: this.charset
				}
			}
		},
		computed: {
			loadTypeCompnent () {
			  switch(this.connection) {
			    case 'mysql':
			      return () => import('../../components/collations/Mysql.vue');
			      break;
			    case 'sqlite':
			      return () => import('../../components/collations/Mysql.vue');
			      break;
			    case 'mongodb':
			      return () => import('../../components/collations/Mysql.vue');
			      break;
			    default:
			      return () => import('../../components/collations/Mysql.vue');
			      break;
			  } 
			},
		}
    }
</script>