<template>
	<div class="row">
	 <div class="col-sm-12">
	    <!--  create form modal -->
	     <div class="modal fade" id="modalcreateForm" tabindex="-1" role="dialog" aria-labelledby="modalcreateForm"
	        aria-hidden="true">
	        <div class="modal-dialog" role="document">
	          <div class="modal-content">
	            <div class="modal-header">
	              	<h4 class="modal-title font-weight-bold">Add Field</h4>
	              	<button type="button" style="color:#fff" class="close" data-dismiss="modal" aria-label="Close">
	                	<span aria-hidden="true">&times;</span>
	              	</button>
	            </div>
	            <div class="modal-body">
			      <div class="modal-body">
			      	<ul class="nav nav-tabs" id="myTab" role="tablist">
			      	  <li class="nav-item">
			      	    <a class="nav-link active" data-toggle="tab" href="#templates" id="templates_link" role="tab"> Default Filed</a>
			      	  </li>
			      	  <li class="nav-item">
			      	    <a class="nav-link" data-toggle="tab" id="new_filed_link" href="#new_filed">New Field</a>
			      	  </li>
			      	</ul>
			      	<div class="tab-content cs-tab-content" id="myTabContent">
			      	<!-- ============= default-field =============-->
			      	  <div class="tab-pane fade show active" id="templates" role="tabpanel">
			      	  	<div class="default-fields-form"> 
			      	  	   <form action="">
		      	  	   		<ul>
		      	  	   		   <li 
		      	  	   		   	class="df-list" 
		      	  	   		   	v-for="(template,index) in templates"
		      	  	   		   	:key="index">
		      	  	   		      <div class="df-name">{{ template.name }}</div>
		      	  	   		        <div class="action-area">
		      	  	   		        	<a 
		      	  	   		        		href="#" 
		      	  	   		        		@click.prevent="addTemplate(template)" 
		      	  	   		        		class="btn btn-success cs-all-btn">Add</a>
		      	  	   		        	<a  
		      	  	   		        		:href="'#template_'+index+1" 
		      	  	   		        		class="btn btn-info edit-field cs-all-btn" 
		      	  	   		        		data-toggle="collapse" 
		      	  	   		        		title="edit"> <i class="fas fa-edit"></i></a>
		      	  	   		        	<a 
		      	  	   		        		href="#" 
		      	  	   		        		@click.prevent="removeTemplate(template)" 
		      	  	   		        		class="btn btn-danger cs-all-btn"  
		      	  	   		        		title="delete"><i class="far fa-trash-alt"></i></a>
		      	  	   		      </div>
  	  	   		      	      		<!-- ================== collapse  Start ================== -->
						           	<div class="collapse" :id="'template_'+index+1">
						           	    <div class="card card-body">
						           	      <form>
						           	        <div class="row">
						           	           <div class="col-md-6">
						           	             <div class="form-group cs-form-group"> 
						           	                <label for="name" class="cs-label">  Name</label>
						           	                <input type="text" class="form-control" v-model="template.name">
						           	             </div>
						           	           </div>
						           	           <div class="col-md-6">
						           	                <div class="form-group cs-form-group"> 
						           	                   <label class="cs-label">Type</label>
						           	                   <component 
						           	                    :is="loadTypeCompnent" 
						           	                    :field="template" 
						           	                    :notSupportIndexs="notSupportIndexs" 
						           	                    :notSupportDefault="notSupportDefault"></component>
						           	                 </div>
						           	           </div>
						           	           <div class="col-md-6">
						           	             <div class="form-group cs-form-group"> 
						           	                <label for="number" class="cs-label">Length</label>
						           	                <input type="number" class="form-control" v-model="template.length">
						           	             </div>
						           	           </div>
						           	           <div class="col-md-6">
						           	             <div class="form-group cs-form-group"> 
						           	                <label for="number" class="cs-label">index</label>
						           	                <select 
						           	                	class="form-control" 
						           	                	:disabled=" !checkSupportedIndex(template) || isNotSupportedIndex" 
						           	                	v-model="template.index">
						           	                  <option value="">Select INDEX</option>
						           	                  <option value="INDEX">INDEX</option>
						           	                  <option value="UNIQUE">UNIQUE</option>
						           	                  <option value="PRIMARY" v-if="!isMongoDB">PRIMARY</option>
						           	                  <option value="TEXT" v-if="isMongoDB">TEXT INDEX</option>
						           	                  <option value="ASC" v-if="isMongoDB">ASCENDING</option>
						           	                  <option value="DESC" v-if="isMongoDB">DESCENDING</option>
						           	                  <option value="UNIQUE_DESC" v-if="isMongoDB">UNIQUE DESCENDING</option>
						           	                  <option value="TTL" v-if="isMongoDB">TTL</option>
						           	                  <option value="SPARSE" v-if="isMongoDB">SPARSE</option>
						           	                  <option value="SPARSE_DESC" v-if="isMongoDB">SPARSE DESCENDING</option>
						           	                  <option value="SPARSE_UNIQUE" v-if="isMongoDB">SPARSE UNIQUE</option>
						           	                  <option value="SPARSE_UNIQUE_DESC" v-if="isMongoDB">SPARSE &amp; UNIQUE DESCENDING</option>
						           	                </select>
						           	             </div>
						           	           </div>
						           	           <div class="col-md-6">
						           	             <div class="form-group cs-form-group">
						           	                 <input 
						           	                 	type="string" 
						           	                 	:disabled=" !checkSupportedDefault(template) || isNotSupportedDefault " 
						           	                 	v-model="template.default" 
						           	                 	class="form-control" />
						           	                 <label class="cs-label">Default</label>
						           	             </div>
						           	           </div>
						           	           <div class="col-md-6">
						           	             <div class="form-group label-group">
						           	                <label>
						           	                  <input 
						           	                    type="checkbox"  
						           	                    v-model="template.notnull"/>  Not Null
						           	                </label> 
						           	                <label>
						           	                  <input 
						           	                    type="checkbox"  
						           	                    v-model="template.unsigned" /> Unsigned   
						           	                </label>
						           	                 <label 
						           	                  :for="'auto_increment'+index+1" 
						           	                  :disabled="(template.autoincrement != true && disableAutoIncrement)"
						           	                 >
						           	                  <input 
						           	                    type="checkbox"  
						           	                    :disabled="(template.autoincrement != true && disableAutoIncrement)" 
						           	                    v-model="template.autoincrement"
						           	                    @change="checkAutoIncrement" />  Auto Increment 
						           	                </label>
						           	             </div>
						           	           </div>
						           	        </div>
						           	      </form>
						           	    </div>
						           	  </div>
					                 <!-- ================== collapse  Start ================== -->
		      	  	   		   </li> 
		      	  	   		</ul>
		      	  	   		<div class="m-footer pull-right">
		      	  	   			<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
	      	  	         	</div>
			      	  	   </form>
			      	  	</div>
			      	</div>
			      	 <!-- ============= Add New Filed =============-->
			      		<div class="tab-pane fade" id="new_filed" role="tabpanel">
  				            <form action="" v-on:submit.prevent="addField(field)">
  				                <div class="form-group cs-form-group">
  			                      	<label class="cs-label">Name</label>
  			                      	<input type="text" name="name" v-model="field.name" class="form-control">
  			                   	</div>
  				                <div class="form-group cs-form-group">
  				                   	<label class="cs-label">Type</label>
  				                   	<component 
  		                                  :is="loadTypeCompnent" 
  		                                  :field="field" 
  		                                  :notSupportIndexs="notSupportIndexs" 
  		                                  :notSupportDefault="notSupportDefault"></component>
  				                </div>
  				                <div class="form-group cs-form-group">
  			                      	<label class="cs-label">Length</label>
  			                      	<input type="number" v-model="field.length" class="form-control" />
  			                   	</div>
  			                   	<div class="form-group cs-form-group">
  			                   		<select 
  			                   			class="form-control" 
  			                   			:disabled="isNotSupportedIndex" 
  			                   			v-model="field.index">
  			                   			<option value="">Select INDEX</option>
  			                   			<option value="INDEX">INDEX</option>
  			                   			<option value="UNIQUE">UNIQUE</option>
  			                   			<option value="PRIMARY" v-if="!isMongoDB">PRIMARY</option>
  			                   			<option value="TEXT" v-if="isMongoDB">TEXT INDEX</option>
  			                   			<option value="ASC" v-if="isMongoDB">ASCENDING</option>
  			                   			<option value="DESC" v-if="isMongoDB">DESCENDING</option>
  			                   			<option value="UNIQUE_DESC" v-if="isMongoDB">UNIQUE DESCENDING</option>
  			                   			<option value="TTL" v-if="isMongoDB">TTL</option>
  			                   			<option value="SPARSE" v-if="isMongoDB">SPARSE</option>
  			                   			<option value="SPARSE_DESC" v-if="isMongoDB">SPARSE DESCENDING</option>
  			                   			<option value="SPARSE_UNIQUE" v-if="isMongoDB">SPARSE UNIQUE</option>
  			                   			<option value="SPARSE_UNIQUE_DESC" v-if="isMongoDB">SPARSE &amp; UNIQUE DESCENDING</option>
  			                   		</select>
  			                   		<label class="cs-label">INDEX</label>
  			                   	</div>
  			                   	<div class="form-group cs-form-group">
  			                      	<input 
  			                      		type="string" 
  			                      		:disabled="isNotSupportedDefault" 
  			                      		v-model="field.default" 
  			                      		class="form-control" />
  			                      	<label class="cs-label">Default</label>
  			                   	</div>
  			                   	<div class="form-group label-group">
  			                   		<label for="notnull">
  			                   			<input 
  			                   				type="checkbox"  
  			                   				v-model="field.notnull" 
  			                   				id="notnull" /> Not Null</label>
  			                   		<label for="unsigned">
  			                   			<input 
  			                   				type="checkbox"  
  			                   				v-model="field.unsigned" 
  			                   				id="unsigned" /> Unsigned</label>
  			                   		<label for="autoincrement" v-bind:class="{ disabled: disableAutoIncrement }">
  			                   			<input 
  			                   				type="checkbox"  
  			                   				id="autoincrement" 
  			                   				v-bind:class="{ disabled: disableAutoIncrement }" 
  			                   				v-model="field.autoincrement" /> Auto Increment</label>
  			                   	</div>
  				                <div class="m-footer pull-right">
  				                  	<button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
  				                  	<button type="submit" class="btn btn-success">Add</button>
  				                  	<button type="button" v-on:click.prevent="saveTemplate(field)" class="btn btn-success">Save Template</button>
  				                </div>
  				            </form>
			      	   </div>
			      	</div>
			      </div>
	            </div>
	          </div>
	        </div>
	      </div>
	  </div>
	</div>
</template>
<script>
	
	import Mysql from '../data-types/Mysql.vue';
	import Sqlite from '../data-types/Sqlite.vue';
	import MongoDB from '../data-types/MongoDB.vue';
	
	export default {
		props: {
			field: Object,
			ids: Array,
			fields: Array,
			templates: Array,
			connection: String,
			notSupportIndexs: Array,
			notSupportDefault: Array,
			disableAutoIncrement: Boolean,
			saveTemplate: Function,
			removeTemplate: Function,
		},
        data() {
        	return {
        		driver: this.connection,
        	};
        },
        computed: {
        	loadTypeCompnent () {
        		switch(this.connection) {
        			case 'mysql':
        				return () => import('../data-types/Mysql.vue');
        				break;
        			case 'sqlite':
        				return () => import('../data-types/Sqlite.vue');
        				break;
        			case 'mongodb':
        				return () => import('../data-types/MongoDB.vue');
        				break;
        			default:
        				return () => import('../data-types/Mysql.vue');
        				break;
        		} 
        	    return this.driver
        	},
        	isNotSupportedIndex: function() {
        		if(this.notSupportIndexs.includes(this.field.type.name)) {
        			return true;
        		}
        		return false;
        	},
        	isNotSupportedDefault: function() {
        		if(this.notSupportDefault.includes(this.field.type.name)) {
        			return true;
        		}
        		return false;
        	},
        	isMongoDB: function(){
        		return (this.connection == 'mongodb');
        	}
        },
        methods: {
        	addTemplate(template) {
        		let field = {...template};
        		field.oldName = "";
        		this.fields.push(field);
        		toastr.success(template.name +' Added Successfully.');
        	},
        	addField(field) {
        		this.fields.push({...field});
        		toastr.success(field.name +' Added Successfully.');
        		this.closeModal();
        	},
        	checkAutoIncrement: function(){
        	  for(let field of this.fields) {
        	    if(field.autoincrement == true) {
        	      this.disableAutoIncrement = true;
        	    }
        	  }

        	  this.disableAutoIncrement = false;
        	},
        	checkSupportedIndex: function(field){
        	  if(this.notSupportIndexs.includes(field.type.name)) {
        	    return false;
        	  }

        	  return true;
        	},
        	checkSupportedDefault: function(field) {
        	  if(this.notSupportDefault.includes(field.type.name)) {
        	    return false;
        	  }

        	  return true;
        	},
        	closeModal: function(){
        	    $('.modal').modal('hide');
        	    $('.modal-backdrop').remove();
        	},
        }
    }
</script>