<template>
    <div class="">
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" 
            :key="key"> {{ databaseError }}
        </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content"> <!-- cs-content start -->
                <div class="dbase-btn btn-area">
                    <a 
                        v-if="hasPermission('record.create') && isRecordModal" 
                        href="#" class="btn btn-success cs-all-btn" 
                        v-on:click.prevent="showRecordForm" 
                        data-toggle="modal" 
                        data-target="#recordModal"> <i class="fas fa-plus"></i>Add Record</a>
                    <router-link 
                        v-if="hasPermission('record.create') && !isRecordModal" 
                        :to="{ name: 'addditRecord', params:{tableName: tableName} }" 
                        class="btn btn-success cs-all-btn">Add Record</router-link>
                </div>

                <div class="row search-area">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="database-table_length">
                            <label>show
                                <select v-model="perPage" @change="reload" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> 
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="database-table_filter" class="dataTables_filter search-bar text-right">
                            <label>Search: <input type="search" v-model="search" @keyup="reload" @keydown="reload" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive record-table" v-if="records.length > 0">
                    <table
                        v-if="hasPermission('record.browse')" 
                        class="table table-bordered">
                        <thead>
                            <tr>
                                <th v-for="(field,index) in browseFields" :key="index">{{ field.display_name }}</th>
                                <th class="action">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-for="(record,index) in records" :key="index">
                                <td  
                                    v-for="(field,key) in browseFields" 
                                    :key="key" 
                                    v-html="displayRecordItem(record, field)"></td>
                                <td class="action">
                                    <router-link 
                                        v-if="hasPermission('record.create') && !isRecordModal" 
                                        :to="{ name: 'addditRecord', params:{tableName: tableName, id: record[findColumn]} }" 
                                        class="btn btn-info cs-all-btn">Edit</router-link>
                                    <button 
                                        v-if="hasPermission('record.update') && isRecordModal" 
                                        v-on:click="editRecordForm(index)" 
                                        class="btn btn-info cs-all-btn" 
                                        data-toggle="modal" 
                                        data-target="#recordModal">Edit</button>
                                    <button 
                                        v-if="hasPermission('record.delete')" 
                                        v-on:click="removeRecord(index)" 
                                        class="btn btn-danger cs-all-btn">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div 
                    v-else 
                    class="database-error alert alert-danger" 
                    role="alert">There is no record available for {{ tableName }}</div>

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div 
                            class="dataTables_info" 
                            id="database-table_info" 
                            role="status" 
                            aria-live="polite">
                            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <pagination 
                            :data="pagination" 
                            :limit="2" 
                            align="right" 
                            @pagination-change-page="fetchDatabaseTables"></pagination>
                    </div>
                </div>
                
            </div>
        </transition>
        
        <record-modals
            v-if="isRenderComponent && isRecordModal"
            :prefix="prefix"  
            :tableName="tableName"  
            :action="action" 
            :fields="addOrEditFields" 
            :records="records" 
            :record="record" 
            :row="row" 
            :userPermissions="userPermissions"
            @fresh="fetchDatabaseTables"
            @reset="resetForm"> 
        </record-modals>

    </div><!-- cs-content start -->
</template>

<script>
    import RecordModals from '../components/modals/RecordModals.vue';
    import Paginate from './partials/Paginate.vue';
    export default {
        props: ["tableName","prefix"],
        components: {
            RecordModals,
            Paginate
        },
        data() {
            return {
                action: 'add',
                isRecordModal: true,
                addOrEditFields: [],
                isRenderComponent: false,
                userPermissions: [],
                findColumn: 'id',
                object: {},
                row: {},
                fields: [],
                browseFields: [],
                createFields: [],
                editFields: [],
                deleteFields: [],
                records: [],
                record: {},
                currentIndex: null,
                pagination: {},
                perPage: 10,
                search: ""
            };
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchDatabaseTables();
        },
        methods: {
            fetchDatabaseTables: function(page = 1) {
                axios.get(`/api${this.prefix}/table/details/${this.tableName}?page=${page}&perPage=${this.perPage}&q=${this.search}`)
                .then(res => {
                    if( res.data.success == true ){
                        this.userPermissions = res.data.userPermissions;
                        this.object = res.data.object;
                        this.perPage = res.data.objectDetails.perPage ? res.data.objectDetails.perPage : 10;
                        this.findColumn = res.data.object.details.findColumn;
                        this.records = res.data.records;
                        this.browseFields = res.data.browseFields;
                        this.deleteFields = res.data.deleteFields;
                        // If Modal is true
                        if(this.isRecordModal) {
                            this.createFields = res.data.createFields;
                            this.editFields = res.data.editFields;
                            this.setDefaultFormValue(res.data.createFields);
                            this.setDefaultFormValue(res.data.editFields);
                        }

                        this.pagination = res.data.pagination;
                        this.isRecordModal = res.data.isRecordModal;
                        this.databaseErrors = [];
                        this.loadComponent();
                    }
                })
                .catch(err => this.displayError(err.response)); 
            },
            reload: _.debounce(function() {
                this.fetchDatabaseTables();
            }, 500),
            showRecordForm: function(){
                this.resetForm();
                this.setDefaultFormValue(this.createFields);
                this.action = 'add';
                this.addOrEditFields = this.createFields;
                this.forceToRerender();
            },
            editRecordForm: function(index) {
                this.resetForm();
                this.action = 'edit';
                this.addOrEditFields = this.editFields;
                this.currentIndex = index;
                this.record = this.records[index];

                for(var field of this.editFields) {
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
                        default:
                            this.row[field.name] = (this.record[field.name] != undefined) ? this.record[field.name] : "";
                            break;
                    }
                }

                this.forceToRerender();
            },
            removeRecord: function(index) {
                var self = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this menu item',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start()
                        var record = this.records[index];
                        var url = '/api'+this.prefix+'/record';
                        
                        axios({
                          method: 'delete',
                          url: url,
                          params: {
                            table: this.tableName,
                            columns: record,
                            fields: this.deleteFields
                          },
                          responseType: 'json',
                        })
                        .then(res => {
                            if( res.data.success == true ){
                                toastr.success("Record Deleted Successfully");
                                self.fetchDatabaseTables();
                                this.$Progress.finish()
                            }
                        })
                        .catch(err => {
                            this.$Progress.fail()
                            this.displayError(err.response);
                        });

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                });
            },
            displayRecordItem: function(record, field) {

                switch(field.type) {
                    case 'relationship':
                        if(Array.isArray(record[field.name])) {

                            var relationship_datas = record[field.name];
                            var html = '<ul>';
                                for(var relationship_data of relationship_datas) {
                                    html += '<li>'+relationship_data[field.displayLabel]+'</li>';
                                }
                                html += '</ul>';

                                return html;
                        }else if(record[field.name] != null && typeof record[field.name] === 'object'){
                            var relationship = record[field.name];
                            return relationship[field.displayLabel];
                        }else {
                            return record[field.name];
                        }
                        break;
                    case 'multiple_images':
                        let images = record[field.name];
                        var html = '<ul class="record-list">';

                        if(images) {
                            for(var image of images) {
                                html += '<li class="record-item"><img src="/storage/dbm/'+this.tableName+'/'+image+'" alt="'+field.name+' Image" class="record-image" /></li>';
                            }
                        }
                        
                        html += '</ul>';
                        return html;
                        break;
                    case 'image':
                        return '<img src="/storage/dbm/'+this.tableName+'/'+record[field.name]+'" alt="'+field.name+' Image" class="record-image" />';
                        break;
                    default:
                        if(Array.isArray(record[field.name])) {

                            var relationship_datas = record[field.name];
                            var html = '<ul>';
                                for(var relationship_data of relationship_datas) {
                                    html += '<li>'+relationship_data+'</li>';
                                }
                                html += '</ul>';

                                return html;
                        }
                        return record[field.name];
                        break;
                }
                
            },
            /*
             * Helpers
             */
            setRelationshipValues: function(field){
                let index = this.currentIndex;
                let relationshipValues = [];

                switch(field.relationship.relationType) {
                    case 'hasMany':
                        var foreignKeys = [];
                        
                        for(var key of this.records[index][field.name]) {
                            relationshipValues.push({
                                name: key[field.relationship.displayLabel], 
                                value: key[field.relationship.localKey] 
                            });
                        }
                        this.row[field.relationship.foreignKey] = relationshipValues;
                        break;
                    case 'belongsToMany':
                        var relatedPivotKeys = [];
                        for(var key of this.records[index][field.name]) {
                            relationshipValues.push({
                                name: key[field.relationship.displayLabel], 
                                value: key[field.relationship.localKey] 
                            });
                        }
                        this.row[field.relationship.relatedPivotKey] = relationshipValues;
                        break;
                    default:
                        if(this.records[index][field.name] != null) {
                            this.row[field.relationship.foreignKey] = [];
                            this.row[field.relationship.foreignKey].push({
                                name: this.records[index][field.name][field.relationship.displayLabel], 
                                value: this.records[index][field.relationship.foreignKey] 
                            });
                        }
                        break;
                }
            },
            setDropdownValues: function(field){

                let index = this.currentIndex;
                let options = field.settings.options;
                let records = this.records[index][field.name];

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
                        if(option.value == this.records[index][field.name]) {
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
            forceToRerender() {
                // Remove my-component from the DOM
                this.isRenderComponent = false;
                this.$nextTick(() => {
                  // Add the component back in
                  this.isRenderComponent = true;
                });
            },
            resetForm: function(){
                this.row= {};
            },
        }
    }
</script>

<style scoped>
    .cs-content {
        overflow: hidden;
    }
</style>