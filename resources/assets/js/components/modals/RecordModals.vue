<template>
	<div class="row">
		<!-- Add -->
		<div 
            v-if="hasPermission('record.create')" 
            class="modal fade" 
            id="recordModal" 
            tabindex="-1" 
            role="dialog" 
            aria-labelledby="recordModalLebel"
		    aria-hidden="true">
		    <div class="modal-dialog" role="document">
		      <div class="modal-content">
		        <div class="modal-header">
		            <h4 class="modal-title font-weight-bold">{{ (action == 'edit') ? 'Update' : 'Create' }} Table</h4>
		            <button type="button" style="color:#fff" class="close" data-dismiss="modal" aria-label="Close">
		                <span aria-hidden="true">&times;</span>
		            </button>
		        </div>
		        <div class="modal-body">
		            <form @submit.prevent="(action == 'edit') ? updateRecord({...newRow}) : createRecord({...newRow})">
		            	<!-- Stop autocomplete for password -->
           				<input type="password" style="display:none">
		                <div class="form-group" v-for="(field,index) in fields" :key="field.id">
		                	<label>{{ field.display_name }}</label>
		                    <div v-if="field.type == 'relationship'">
		                        <multiselect
		                        	v-if="field.relationship.relationType != 'belongsToMany'"
		                            v-model="newRow[field.relationship.foreignKey]" 
		                            :options="getRelationshipOptions(field)"
		                            :multiple="(field.relationship.relationType == 'belongsToMany' || field.relationship.relationType == 'hasMany') ? true : false" 
		                            :close-on-select="true" 
		                            :clear-on-select="false" 
		                            :preserve-search="true" 
		                            placeholder="'Pick some'" 
		                            label="name" 
		                            track-by="name" 
		                            :preselect-first="false"
		                            id="update_relation"></multiselect>
		                          <multiselect
		                        	 v-if="field.relationship.relationType == 'belongsToMany'"
		                            v-model="newRow[field.relationship.relatedPivotKey]" 
		                            :options="getRelationshipOptions(field)"
		                            :multiple="(field.relationship.relationType == 'belongsToMany' || field.relationship.relationType == 'hasMany') ? true : false" 
		                            :close-on-select="true" 
		                            :clear-on-select="false" 
		                            :preserve-search="true" 
		                            placeholder="'Pick some'" 
		                            label="name" 
		                            track-by="name" 
		                            :preselect-first="false"
		                            id="update_belongs_to_many_relation"></multiselect>
		                    </div>
                            <div v-else-if="field.type == 'dropdown' || field.type == 'multiple_dropdown'">
                                  <multiselect
                                    v-if="field.settings != null && field.settings.options != null"
                                    v-model="newRow[field.name]" 
                                    :options="getFieldOptions(field)"
                                    :multiple="checkMultiple(field) ? true : false" 
                                    :close-on-select="true" 
                                    :clear-on-select="false" 
                                    :preserve-search="true" 
                                    placeholder="'Pick some'" 
                                    label="name" 
                                    track-by="name" 
                                    :preselect-first="false"
                                    id="update_relation"></multiselect>
                            </div>
		                    <div v-else-if="field.type == 'file' || 
		                    	field.type == 'image' || 
		                    	field.type == 'multiple_images'">
		                    		<div class="custom-file">
		                    		    <input 
		                    		        :type="geType(field)"  
		                    		        :id="'customFile_'+(index+1)" 
		                    		        class="custom-file-input form-control" 
		                    		        @change="getFile(field, $event)" 
		                    		        :accept="checkFileType(field)" 
		                    		        :multiple="checkMultiple(field)" />
		                    		    <label class="custom-file-label" :for="'customFile_'+(index+1)">Choose {{ field.type }}</label>
		                    		</div>
		                    </div>
		                    <div v-else-if="field.type == 'textarea'">
		                    	<textarea  
		                    		v-model="newRow[field.name]" 
		                    		class="form-control"></textarea>
		                    </div>
		                    <div v-else-if="field.type == 'unique'">
		                    	<input 
		                    		type="text" 
		                    		v-model="newRow[field.name]" 
		                    		class="form-control" 
		                    		@change="checkUniqueNumber(fields,field)" 
		                    		@keyup="checkUniqueNumber(field)" 
		                    		@keydown="checkUniqueNumber(field)" />
		                    	<span class="unique" style="color: red" v-html="errors.unique"></span>
		                    	<div class="unique_unavailable_values_container">
		                    		<span>You can't choose these {{ field.name }}'s</span>
		                    		<span class="unique_unavailable_values">{{  unAvailableUniqueValues(field).join(', ') }}</span>
		                    	</div>
		                    </div>
		                    <div v-else-if="field.type == 'unique_number'">
		                    	<input 
		                    		type="number" 
		                    		v-model="newRow[field.name]" 
		                    		class="form-control" 
		                    		@change="checkUniqueNumber(fields,field)" 
		                    		@keyup="checkUniqueNumber(field)" 
		                    		@keydown="checkUniqueNumber(field)">
		                    	<span class="unique_error" v-html="errors.unique"></span>
		                    	<div class="unique_unavailable_values_container">
		                    		<span>You can't choose these {{ field.name }}'s</span>
		                    		<span class="unique_unavailable_values">{{  unAvailableUniqueValues(field).join(', ') }}</span>
		                    	</div>
		                    </div>
		                    <div v-else-if="field.type == 'checkbox'">
		                        <div class="onoffswitch">
		                          <input 
		                          	type="checkbox" 
		                          	v-model="newRow[field.name]" 
		                          	id="makeModel" 
		                          	class="onoffswitch-checkbox">
		                          <label 
		                          	for="makeModel" 
		                          	class="onoffswitch-label">
		                            <span class="onoffswitch-inner"></span>
		                            <span class="onoffswitch-switch"></span>
		                          </label>
		                        </div>
		                    </div>
                            <div v-else-if="field.type == 'multiple_checkbox'" :multiple="checkMultiple(field)">
                                <label 
                                	class="checkbox" 
                                	:for="'checkbox_'+(index+1)" 
                                	v-for="(option,index) in getFieldOptions(field)">{{ option.name }}
                                  <input 
                                  	type="checkbox" 
                                  	:id="'checkbox_'+(index+1)" 
                                  	:value="option.value" 
                                  	v-model="newRow[field.name]">
                                  <span class="checkmark"></span>
                                </label>
                            </div>
                            <div v-else-if="field.type == 'radio'">
            	        		<label 
            	        			class="radio-btn" 
            	        			:for="'radio_'+(index+1)" 
            	        			v-for="(option,index) in getFieldOptions(field)">{{ option.name }}
            	        	 	 	<input 
            	        	 	 		type="radio" 
            	        	 	 		:id="'radio_'+(index+1)" 
            	        	 	 		:value="option.value" 
            	        	 	 		v-model="newRow[field.name]">
            	        	 		<span class="checkradio"></span>
            	        		</label>
                            </div>
                            <div v-else-if="field.type == 'rich_text'">
                                <rich-editor 
                                	:name="field.name" 
                                	:content="newRow[field.name]" 
                                	v-model="newRow[field.name]"></rich-editor>
                            </div>
                            <div v-else-if="field.type == 'code_editor'">
                                <code-editor 
                                    :name="field.name" 
                                    :content="row[field.name]" 
                                    v-model="row[field.name]"></code-editor>
                            </div>
                            <div v-else-if="field.type == 'markdown_editor'" class="markdown_editor">
                                <mardown-code-editor 
                                	:name="field.name" 
                                	:content="newRow[field.name]" 
                                	v-model="newRow[field.name]"></mardown-code-editor>
                            </div>
		                    <div v-else>
		                        <input 
		                        	:type="field.type" 
		                        	v-model="newRow[field.name]" 
		                        	class="form-control">
		                    </div>
		                    
		                </div>
		                <div class="m-footer">
		                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
		                    <button 
		                    	type="submit" 
		                    	class="btn btn-info">{{ (action == 'edit') ? 'Update' : 'Add' }}</button>
		                </div>
		            </form>
		        </div>
		      </div>
		    </div>
		</div>
	</div>
</template>
<script>
	import RichEditor from '../../views/editors/RichEditor.vue'
    import MarkdownCodeEditor from '../../views/editors/Markdown.vue'
    import CodeEditor from '../../views/editors/CodeEditor.vue'

	export default {
		props: {
			prefix: String,
			tableName: String,
			action: String,
			fields: Array,
			records: Array,
			record: Object,
			row: Object,
			userPermissions: Array,
		},
		components: {
            CodeEditor,
            RichEditor,
            'mardown-code-editor':MarkdownCodeEditor
        },
        data() {
        	return {
        		errors: {
        			unique: ""
        		},
        		oldRow: { ...this.row },
        		newRow: { ...this.row },
        	};
        },
        created() {
        	axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
        },
        methods: {
        	createRecord: function(row){
                this.$Progress.start()
        	    let formData = this.prepareFormData(row, this.fields);

        	    axios({
        	        method: 'post',
        	        url: '/api'+this.prefix+'/record',
        	        data: formData,
        	        responseType: 'json',
        	    }).then(res => {
        	        if( res.data.success == true ){
        	            toastr.success("Record Added successfully.")
        	            this.$emit('fresh')
        	            this.$emit('reset')
        	            this.closeModal()
                        this.$Progress.finish()
        	        }
        	        
        	    })
        	    .catch(err => {
                    this.$Progress.fail()
        	        this.displayError(err.response)              
        	    });
        	},
        	updateRecord: function(row){
                this.$Progress.start()
                let formData = this.prepareFormData(row, this.fields, 'update');

                axios({
                    method: 'post',
                    url: '/api'+this.prefix+'/record',
                    headers: {
                        'Content-Type':'multipart/form-data'
                    },
                    data: formData,
                    responseType: 'json',
                }).then(res => {
                    if( res.data.success == true ){
                        toastr.success("Record Updated successfully.");
                        this.$emit('fresh')
        	            this.$emit('reset')
        	            this.closeModal()
                        this.$Progress.finish()
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response)               
                });
            },
        	prepareFormData: function(row, fields, action = 'create') {

                let formData = new FormData();

                for(let field of fields) {

                    switch(field.type) {
                        case 'file':
                        case 'image':
                        case 'multiple_images':
                            let files = row[field.name];
                            for(let index in files) {
                                formData.append(`${field.name+'['+index+']'}`, files[index]);
                            }
                            break;
                        case 'relationship':
                            let fieldName = "";

                            switch(field.relationship.relationType) {
                                case 'hasMany':
                                    fieldName = field.relationship.foreignKey;
                                    break;
                                case 'belongsToMany':
                                    fieldName = field.relationship.relatedPivotKey;
                                    break;
                                default:
                                    fieldName = field.relationship.foreignKey;
                                    break;
                            }

                            row = this.getSelectOptions(row, fieldName, field);
                            break;
                        case 'dropdown':
                        case 'multiple_dropdown':
                            row = this.getSelectOptions(row, field.name);
                            break;
                        default:
                            formData.append(field.name, row[field.name]);
                            break;
                    }
                }

                formData.append('table', this.tableName);
                formData.append('fields', JSON.stringify(fields));
                formData.append('columns', JSON.stringify(row));

                if(action == 'update') {
                    let record = this.record;
                    for(let column in row) {
                        record[column] = row[column];
                    }

                    formData.append('columns', JSON.stringify(record));
                    formData.append('_method', 'put');
                }

                return formData;
            },
            getSelectOptions: function(row, fieldName, field = null){
                let options = row[fieldName];
                if(Array.isArray(options)) {
                    row[fieldName] = [];
                    for(let option of options) {
                        row[fieldName].push(option.value);
                        if(field != null && 
                            field.relationship.relationType != 'hasMany' && 
                            field.relationship.relationType != 'belongsToMany') {

                            row[fieldName] = option.value;
                            return row;
                        }
                    }
                    
                }else if(options !== null && typeof options === 'object') {
                    row[fieldName] = options.value;
                }

                return row;
            },
        	geType: function(field) {
        		if(field.type == 'file' || field.type == 'image' || field.type == 'multiple_images') {
        			return 'file';
        		}
        	},
        	getFile: function(field, e) {
        		var files = e.target.files;
        		this.newRow[field.name] = files;
        	},
            getRelationshipOptions: function(field) {
                let options = field.foreignTableData;
                let results = [];
                for(let option of options) {
                    results.push({
                        name: option[field.relationship.displayLabel], 
                        value: option[field.relationship.localKey], 
                        id: option[field.relationship.localKey] 
                    });
                }

                return results;
            },
        	getFieldOptions: function(field) {
        		let options = field.settings ? field.settings.options : field.options;

        		if(!Array.isArray(options)) {
        			options = field.options;
        		}

        		return options;
        	},
        	checkFileType: function(field) {
        		if(field.type == 'image' || field.type == 'multiple_images') {
        			return "image/*";
        		}

        		return false;
        	},
        	checkMultiple: function(field) {
        		if(field.type == 'multiple_images' || field.type == 'multiple_dropdown') {
        			return true;
        		}

        		return false;
        	},
        	unAvailableUniqueValues: function(field, current = null){
        		let items = [];
        		for(let record of this.records) {
        			if(current != null && record[field.name] == current) {
        				continue;
        			}
        			items.push(record[field.name]);
        		}

        		return items;
        	},
        	checkUniqueNumber: function(field) {
        		this.errors.unique = "";
        		if(field.name != undefined) {
        			for(let record of this.records) {
        				if(record[field.name] != this.oldRow[field.name] && record[field.name] == this.newRow[field.name]) {
        					this.errors.unique = "Please enter a unique '"+field.name+"'";
        					break;
        				}
        			}
        		}
        		
        	},
        	closeModal: function(){
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>

<style scoped>
	.unique_unavailable_values_container {
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.unique_unavailable_values {
		color: red;
	}
	.unique_error {
		color: red;
	}
	#recordModal .modal-dialog {
        max-width: 60%;
    }
</style>