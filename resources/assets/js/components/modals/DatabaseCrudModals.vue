<template>
	<div class="row">
		<!--  Relationship modal -->
		<div class="modal fade" id="relationshipModal" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h4 class="modal-title font-weight-bold">{{ (action =='edit') ? 'Update' : 'Make' }} a Relation</h4>
		        <button type="button" style="color:#fff" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body relationship-info">
		       	<form action="" v-on:submit.prevent="(action == 'edit') ? updateRelation() : addRelation()">
			        <div class="row"> 
			            <div class="form-group col-sm-6">
			                <label>Relationship Type</label>
			                <select 
			                	id="relationship_type" 
			                	class="form-control" 
			                	@change="changeRelationshipType" 
			                	v-model="relationship.type">
			                   	<option value="hasOne">Has One</option>
			                    <option value="hasMany">Has Many</option>
			                    <option value="belongsTo">Belongs To</option>
			                    <option value="belongsToMany">Belongs To Many</option>
			                </select>
			            </div>
			            <div class="col-md-6">
			                 <label>Foreign Table</label>
			                 <select 
			                 	id="relationship_table" 
			                 	class="form-control" 
			                 	@change="changeForeignTable($event)" 
			                 	v-model="relationship.foreignTable">
			                   <option 
			                   	v-for="(relationship_table,index) in relationship.tables" 
			                   	:key="index" 
			                   	:value="relationship_table">{{ relationship_table }}</option>
			                 </select>
			            </div>
			        </div>

			        <div class="row">
			        	<div :class="(relationship.type == 'belongsToMany') ? 'col-md-6' : 'col-md-12'">
			        		<label>Foreign Table Model <span class="badge badge-success">{{ relationship.foreignTable }}</span></label>
			        		<input 
			        			type="text" 
			        			class="form-control" 
			        			v-model="relationship.foreignModel" 
			        			placeholder="Fully Qualified Model Name" />
			        	</div>
			        	<div class="col-md-6" v-if="relationship.type == 'belongsToMany'">
			        	     <label>Pivot Table</label>
			        	    <select class="form-control" @change = "changePivotTable($event)" v-model="relationship.pivotTable">
			        	        <option 
			        	        	v-for="(pivot_table,index) in relationship.tables" 
			        	        	:key="index" 
			        	        	:value="pivot_table" >{{ pivot_table }}</option>
			        	    </select>
			        	</div>
			        </div>
			        
			        <div class="realtionship_key_details">
			            <div class="row" v-if="relationship.type == 'hasOne' || relationship.type == 'hasMany'">
			                <div class="col-md-6">
			                    <label>Local Column <span class="badge badge-success">{{ relationship.localTable }}</span></label>
			                    <select id="relationship_column" class="form-control" v-model="relationship.localKey">
			                       <option 
			                       	v-for="(relationship_field,index) in relationship.localFields" 
			                       	:key="index" 
			                       	:value="relationship_field.name">{{ relationship_field.name }}</option>
			                    </select>
			                </div>
			                <div class="col-md-6">
			                    <label>Foreign Column <span class="badge badge-success">{{ relationship.foreignTable }}</span></label>
			                    <select id="relationship_column" class="form-control" v-model="relationship.foreignKey">
			                       <option 
			                       	v-for="(relationship_field,index) in relationship.foreignFields" 
			                       	:key="index" 
			                       	:value="relationship_field.name">{{ relationship_field.name }}</option>
			                    </select>
			                </div>
			            </div>

			            <div class="row" v-if="relationship.type == 'belongsTo'">
			              <div class="col-md-6">
			                  <label>Local Column <span class="badge badge-success">{{ relationship.foreignTable }}</span></label>
			                  <select id="relationship_column" class="form-control" v-model="relationship.localKey">
			                     <option 
			                     	v-for="(relationship_field,index) in relationship.foreignFields" 
			                     	:key="index" 
			                     	:value="relationship_field.name">{{ relationship_field.name }}</option>
			                  </select>
			              </div>
			              <div class="col-md-6">
			                <label>Foreign Column <span class="badge badge-success">{{ relationship.localTable }}</span></label>
			                <select id="relationship_column" class="form-control" v-model="relationship.foreignKey">
			                   <option 
			                   	v-for="(relationship_field,index) in relationship.localFields" 
			                   	:key="index" 
			                   	:value="relationship_field.name">{{ relationship_field.name }}</option>
			                </select>
			              </div>
			            </div>

			            <div class="row" v-if="relationship.type == 'belongsToMany'">
			                <div class="col-md-6">
			                    <label>Parent Pivot Key <span class="badge badge-success">From</span></label>
			                    <select 
			                    	id="relationship_column" 
			                    	class="form-control" 
			                    	v-model="relationship.parentPivotKey">
			                       <option 
			                       	v-for="(belongsToManyField,index) in relationship.belongsToManyFields" 
			                       	:key="index" 
			                       	:value="belongsToManyField.name">{{ belongsToManyField.name }}</option>
			                    </select>
			                </div>
			                <div class="col-md-6">
			                    <label>Related Pivot Key <span class="badge badge-success">To</span></label>
			                    <select name="" id="relationship_column" class="form-control" v-model="relationship.relatedPivotKey">
			                       <option 
			                       	v-for="(belongsToManyField,index) in relationship.belongsToManyFields" 
			                       	:key="index" 
			                       	:value="belongsToManyField.name">{{ belongsToManyField.name }}</option>
			                    </select>
			                </div>
			            </div>
			        </div>

			        <div class="realtionship_details">
			           <div class="form-group">
			              <label for="">Display the <span class="badge badge-success">{{ relationship.foreignTable }}</span> </label>
			               <select name="" id="" class="form-control" v-model="relationship.displayLabel">
			                 <option 
			                 	v-for="(relationship_field,index) in relationship.foreignFields" 
			                 	:key="index" 
			                 	:value="relationship_field.name">{{ relationship_field.name }}</option>
			               </select>
			           </div>  
			        </div>

		          	<div class="m-footer text-right">
		            	<button type="button" class="btn btn-danger cs-all-btn" data-dismiss="modal">Close</button> 
		            	<button 
		            		type="submit" 
		            		class="btn btn-success cs-all-btn">
		            		{{ (action == 'add') ? 'Add' : 'Update' }} Relationship
		            	</button>
		         	</div>
		        </form>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
</template>
<script>
	export default {
		props: {
			action: String,
			relationship: Object,
			changeRelationshipType: Function,
			changeForeignTable: Function,
			changePivotTable: Function,
			addRelation: Function,
			updateRelation: Function,
		}
    }
</script>