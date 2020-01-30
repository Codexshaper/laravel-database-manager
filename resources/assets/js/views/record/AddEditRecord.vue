<template>
    <div>
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" :key="key"> {{ databaseError }} </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content">
                <div 
                    class="database-success alert alert-success" 
                    role="alert">
                        <router-link 
                            v-if="action == 'edit'" 
                            :to="{ name: 'addditRecord', params:{tableName: tableName} }" 
                            class="btn btn-success cs-all-btn">
                            <i class="fas fa-plus"></i>Create Record</router-link>
                        <router-link 
                            :to="{ name: 'record', params:{tableName: tableName} }" 
                            class="btn btn-success cs-all-btn">
                            <i class="fas fa-reply"></i> Records</router-link>
                    </div>
                <form 
                    v-on:submit.prevent="(action == 'edit') ? updateRecord(row) : createRecord(row)" 
                    autocomplete="off">
                    <!-- Stop autocomplete for password -->
                    <input type="password" style="display:none">
                    <div class="form-group" v-for="(field,index) in fields" :key="field.id">
                        <label>{{ field.display_name }}</label>
                        <div v-if="field.type == 'relationship'">
                            <multiselect
                                v-if="field.relationship.relationType != 'belongsToMany'"
                                v-model="row[field.relationship.foreignKey]" 
                                :options="getRelationshipOptions(field)"
                                :multiple="(field.relationship.relationType == 'belongsToMany' || field.relationship.relationType == 'hasMany') ? true : false" 
                                :close-on-select="true" 
                                :clear-on-select="false" 
                                :preserve-search="true" 
                                placeholder="'Pick some'" 
                                label="name" 
                                track-by="name" 
                                :preselect-first="false"
                                id="relationship"
                                 @input="changeInputValue(field.relationship.foreignKey)"
                              >
                              </multiselect>
                              <multiselect
                                v-if="field.relationship.relationType == 'belongsToMany'"
                                v-model="row[field.relationship.relatedPivotKey]" 
                                :options="getRelationshipOptions(field)"
                                :multiple="(field.relationship.relationType == 'belongsToMany' || field.relationship.relationType == 'hasMany') ? true : false" 
                                :close-on-select="true" 
                                :clear-on-select="false" 
                                :preserve-search="true" 
                                placeholder="'Pick some'" 
                                label="name" 
                                track-by="name" 
                                :preselect-first="false"
                                id="belongs_to_many_relationship"
                                @input="changeInputValue(field.relationship.relatedPivotKey)"
                              >
                              </multiselect>
                        </div>
                        <div 
                            v-else-if="field.type == 'dropdown' || field.type == 'multiple_dropdown'">
                              <multiselect
                                v-if="field.settings != null && field.settings.options != null"
                                v-model="row[field.name]" 
                                :options="getFieldOptions(field)"
                                :multiple="checkMultiple(field) ? true : false" 
                                :close-on-select="true" 
                                :clear-on-select="false" 
                                :preserve-search="true" 
                                placeholder="'Pick some'" 
                                label="name" 
                                track-by="name" 
                                :preselect-first="false"
                                id="dropdown"
                                :class="field.name"
                                @input="changeInputValue(field.name)"
                              >
                              </multiselect>
                        </div>
                        <div 
                            v-else-if="field.type == 'file' || 
                            field.type == 'image' || 
                            field.type == 'multiple_images'">
                            <div class="custom-file">
                                <input 
                                    :type="geType(field)" 
                                    :name="field.name" 
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
                                :name="field.name" 
                                v-model="row[field.name]" 
                                class="form-control"></textarea>
                        </div>
                        <div v-else-if="field.type == 'unique'">
                            <input 
                                type="text" 
                                :name="field.name" 
                                v-model="row[field.name]" 
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
                                :name="field.name" 
                                v-model="row[field.name]" 
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
                              <input type="checkbox" name="onoffswitch" v-model="row[field.name]" id="makeModel" class="onoffswitch-checkbox">
                              <label for="makeModel" class="onoffswitch-label">
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
                                v-model="row[field.name]">
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
                                    v-model="row[field.name]" 
                                    @change="changeInputValue(field.name)">
                                <span class="checkradio"></span>
                            </label>
                        </div>
                        <div v-else-if="field.type == 'rich_text'">
                            <rich-editor 
                                :name="field.name" 
                                :content="row[field.name]" 
                                v-model="row[field.name]"></rich-editor>
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
                                :content="row[field.name]" 
                                v-model="row[field.name]"></mardown-code-editor>
                        </div>
                        <div v-else>
                            <input 
                                :type="field.type"  
                                v-model="row[field.name]" 
                                class="form-control">
                        </div>
                        
                    </div>
                    <div class="m-footer">
                        <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                        <button 
                            type="submit" 
                            class="btn btn-success waves-effect">{{ (action == 'edit') ? 'Update' : 'Add' }}</button>
                    </div>
                </form>
            </div>
        </transition>
    </div>
</template>
<script>

    import RichEditor from '../editors/RichEditor.vue'
    import MarkdownCodeEditor from '../editors/Markdown.vue'
    import CodeEditor from '../editors/CodeEditor.vue'

    export default {
        props: ['prefix','tableName', 'id'],
        components: {
            CodeEditor,
            RichEditor,
            'mardown-code-editor':MarkdownCodeEditor
        },
        data() {
            return {
                action: "add",
                databaseSeuccess: "",
                errors: {
                    unique: ""
                },
                oldRow: {},
                row: {},
                rowField: {...this.row},
                userPermissions: [],
                fields: [],
                browseFields: [],
                createFields: [],
                editFields: [],
                deleteFields: [],
                records: [],
                record: {},
                content: "<p>Hello There</p>",
                richContent:"",
                markdownContent: "",
            };
        },
        created() {
            if(this.id) {
                this.action = "edit";
            }
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchTableDetails();
        },
        methods: {
            fetchTableDetails: function() {
                axios(`/api${this.prefix}/table/details/${this.tableName}`,{
                  params: {
                    findValue: this.action == 'edit' ? this.id : null
                  }
                }).then(res => {
                    if( res.data.success == true ){
                        this.userPermissions = res.data.userPermissions;
                        this.createFields = res.data.createFields;
                        this.editFields = res.data.editFields;
                        this.browseFields = res.data.browseFields;
                        this.deleteFields = res.data.deleteFields;
                        this.records = res.data.records;
                        this.row = {};
                        this.fields = this.action == 'edit' ? res.data.editFields : res.data.createFields;
                        this.setDefaultFormValue(this.fields);

                        if(this.action == 'edit') {
                            this.record = res.data.record;
                            this.setEditFormData();
                        }

                        this.loadComponent();
                    }
                    
                })
                .catch(err => this.displayError(err.response));
            },
            createRecord: function(row){
                this.$Progress.start();
                let formData = this.prepareFormData(row, this.createFields);
                axios({
                    method: 'post',
                    url: '/api'+this.prefix+'/record',
                    headers: {
                        'Content-Type':'multipart/form-data'
                    },
                    data: formData,
                    responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        toastr.success("Record Added successfully.");
                        let object = res.data.object;
                        let table = res.data.table;
                        this.$router.replace({
                            name: 'addditRecord', 
                            params:{
                                tableName: this.tableName, 
                                id: table[object.details.findColumn]
                            }
                        });
                        this.$Progress.finish()
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response);                
                });
            },
            updateRecord: function(row){
                this.$Progress.start()
                let formData = this.prepareFormData(row, this.editFields, 'update');
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
                        this.fetchTableDetails();
                        this.$Progress.finish();
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response);                
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
            setRelationshipValues: function(field){
                let relationshipValues = [];

                switch(field.relationship.relationType) {
                    case 'hasMany':
                        var foreignKeys = [];
                        
                        for(var key of this.record[field.name]) {
                            relationshipValues.push({
                                name: key[field.relationship.displayLabel], 
                                value: key[field.relationship.localKey] 
                            });
                        }
                        this.row[field.relationship.foreignKey] = relationshipValues;
                        break;
                    case 'belongsToMany':
                        var relatedPivotKeys = [];
                        for(var key of this.record[field.name]) {
                            relationshipValues.push({
                                name: key[field.relationship.displayLabel], 
                                value: key[field.relationship.localKey] 
                            });
                        }
                        this.row[field.relationship.relatedPivotKey] = relationshipValues;
                        break;
                    default:
                        if(this.record[field.name] != null) {
                            this.row[field.relationship.foreignKey] = [];
                            this.row[field.relationship.foreignKey].push({
                                name: this.record[field.relationship.displayLabel], 
                                value: this.record[field.relationship.foreignKey] 
                            });
                        }
                        break;
                }
            },
            setDropdownValues: function(field){
                let options = field.settings.options;
                let records = this.record[field.name];

                if(!Array.isArray(options)) {
                    options = field.options;
                }

                this.row[field.name] = [];

                if(Array.isArray(records)) {
                    for(let record of records) {
                        for(let option of options) {
                            if(option.value == record) {
                                this.row[field.name].push({
                                    name: option.name, 
                                    value: option.value
                                });
                            }
                        }
                    }
                }else {
                    for(let option of options) {
                        if(option.value == this.record[field.name]) {
                            this.row[field.name].push({
                                name: option.name, 
                                value: option.value
                            });
                        }
                    }
                }
            },
            setDefaultFormValue(fields) {
                for(var key in fields) {
                    var field = fields[key];
                    switch(field.type) {
                        case 'relationship':
                            if(field.relationship.relationType == 'hasMany') {
                                this.row[field.relationship.foreignKey] = [];

                            } else if(field.relationship.relationType == 'belongsToMany') {
                                this.row[field.relationship.relatedPivotKey] = [];
                                
                            } else {
                                this.row[field.relationship.foreignKey] = "";
                            }
                            break;
                        case 'multiple_images':
                        case 'multiple_dropdown':
                        case 'multiple_checkbox':
                            this.row[field.name] = [];
                            break;
                        default:
                            this.row[field.name] = null;
                            break;
                    }
                }
            },
            setEditFormData: function(){
                for(var field of this.editFields) {
                    let value = this.record[field.name];
                    switch(field.type) {
                        case 'relationship':
                            this.setRelationshipValues(field);
                            break;
                        case 'dropdown':
                        case 'multiple_dropdown':
                            this.setDropdownValues(field);
                            break;
                        case 'file':
                        case 'image':
                        case 'multiple_images':
                            this.row[field.name] = null;
                            break;
                        case 'checkbox':
                            this.row[field.name] = Boolean(value);
                            break;
                        case 'multiple_checkbox':
                            this.row[field.name] = Array.isArray(value) ? value : [];
                            break;
                        default:
                            this.row[field.name] = (value != undefined) ? value : "";
                            break;
                    }
                }
            },
            geType: function(field) {
                if(field.type == 'file' || field.type == 'image' || field.type == 'multiple_images') {
                    return 'file';
                }
            },
            getFile: function(field, e) {
                var files = e.target.files;
                this.row[field.name] = files;
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
                }else if(field.type == 'multiple_checkbox') {
                    if(this.action == 'add'){
                        this.row[field.name] = [];
                    }
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
                        if(record[field.name] != this.oldRow[field.name] && record[field.name] == this.row[field.name]) {
                            this.errors.unique = "Please enter a unique '"+field.name+"'";
                            break;
                        }
                    }
                }
            },
            changeInputValue: function(fieldName) {
                let row = {...this.row};
                Vue.delete(this.row, fieldName);
                this.row[fieldName] = row[fieldName];
            }
        },
    }
</script>

<style>
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
</style>