<template>
  <div class="">
    <div 
      class="database-error alert alert-danger" 
      role="alert" 
      v-for="(databaseError,key) in databaseErrors" 
      :key="key"> {{ databaseError }} </div>
    <transition name="fade" mode="out-in">
      <div v-if="isLoaded" class="vue-content">
          <div class="crud-btn-area">
            <p> {{ tableName | capitalize }} Details</p>
          </div>
          <form 
            v-on:submit.prevent="addOrEditCrud" 
            v-if="hasPermission(isCrudExists ? 'crud.update' : 'crud.create')">
              <!-- Object -->
              <div class="table-details">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">Table Name <span class="badge badge-success">required</span></label> 
                      <input 
                        type="text" 
                        disabled="disabled" 
                        class="form-control" 
                        :value="table.name">
                    </div>
                  </div> 
                  <div class="col-sm-6">
                    <div class="form-group">
                      <p>Create Model <span class="badge badge-success">optional</span></p> 
                      <div class="onoffswitch">
                        <input 
                          type="checkbox" 
                          name="onoffswitch" 
                          v-model="table.makeModel" 
                          id="'makeModel" 
                          class="onoffswitch-checkbox">
                        <label for="'makeModel" class="onoffswitch-label">
                          <span class="onoffswitch-inner"></span>
                          <span class="onoffswitch-switch"></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div> 
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="">URL Slug (must be unique) <span class="badge badge-success">required</span></label> 
                      <input type="text" class="form-control" v-model="table.slug">
                    </div> 
                    <div class="form-group">
                      <label for="model_name">Model Name <span class="badge badge-success">required</span></label> 
                      <input type="text" class="form-control" v-model="table.model">
                    </div>
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="">Find Column <span class="badge badge-success">required</span></label>
                          <select class="form-control" v-model="table.findColumn">
                             <option 
                               v-for="(relationship_field,index) in relationship.localFields" 
                               :key="index" 
                               :value="relationship_field.name"> {{ relationship_field.name }} </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="">Search Column <span class="badge badge-success">optional</span></label> 
                          <select class="form-control" v-model="table.searchColumn">
                             <option value="">Select Field</option>
                             <option 
                               v-for="(relationship_field,index) in relationship.localFields" 
                               :key="index" 
                               :value="relationship_field.name"> {{ relationship_field.name }} </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="">Pagination <span class="badge badge-success">optional</span></label> 
                          <select class="form-control" v-model="table.perPage">
                             <option value="">Select Per Page</option>
                             <option value="1">1</option>
                             <option value="5">5</option>
                             <option value="10">10</option>
                             <option value="20">20</option>
                             <option value="50">50</option>
                             <option value="100">100</option>
                             <!-- <input type="number" value=""> -->
                          </select>
                        </div>
                      </div>
                    </div>
                  </div> 
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Display Name (Plural) <span class="badge badge-success">required</span></label> 
                      <input type="text" class="form-control" v-model="table.display_name">
                    </div> 
                    <div class="form-group">
                      <label>Controller Name <span class="badge badge-success">optional</span></label> 
                      <input type="text" class="form-control" v-model="table.controller">
                    </div> 
                    <div class="row" style="margin-top: 15px;">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="policy_name">Order column <span class="badge badge-success">required</span></label> 
                          <select class="form-control" v-model="table.orderColumn">
                            <option value="">Select Field</option>
                            <option 
                              v-for="(relationship_field,index) in relationship.localFields" 
                              :key="index" 
                              :value="relationship_field.name"> {{ relationship_field.name }} </option>
                          </select>
                        </div>
                      </div> 
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="policy_name">Order display column <span class="badge badge-success">optional</span></label>
                          <select class="form-control" v-model="table.orderDisplayColumn">
                             <option value="">Select Field</option>
                             <option 
                              v-for="(relationship_field,index) in relationship.localFields" 
                              :key="index" 
                              :value="relationship_field.name"> {{ relationship_field.name }} </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="">Order direction <span class="badge badge-success">required</span></label> 
                          <select class="form-control" v-model="table.orderDirection">
                             <option value="ASC"> Ascending</option>
                             <option value="DESC" selected=""> Descending </option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Fields -->
                  <div class="table-field-details">
                    <crud-fields
                      :tableName="tableName"
                      :fields="fields"
                      :driver="driver"
                      :prefix="prefix"
                      :isCrudExists="isCrudExists"
                      :relationship="relationship"
                      :fetchDatabaseTables="fetchDatabaseTables"
                      :userPermissions="userPermissions"
                    ></crud-fields>
                    <div class="text-right">
                      <input 
                        type="submit" 
                        class="btn btn-info " 
                        :value="this.isCrudExists ? 'Update' : 'Save'">
                    </div>
              </div>
          </form>

      </div>
    </transition>
  </div>
</template>

<script>
    
    import CrudFields from '../crud/CrudFields.vue';

    export default {
        props: ["tableName","prefix"],
        components: {
            CrudFields
        },
        data() {
            return {
                userPermissions: [],
                table: {},
                fields: [],
                columns: [],
                localTableDetails: [],
                foreignTableDetails: [],
                relationship: {
                    tables: [],
                    type: '',
                    foreignTable: '',
                    foreignModel: '',
                    displayLabel: '',
                    foreignFields: [],
                    localTable: '',
                    localModel: '',
                    localFields: [],
                    localKey: '',
                    foreignKey: '',
                    // Many to Many
                    belongsToManyFields: [],
                    pivotTable: '',
                    parentPivotKey: '',
                    relatedPivotKey: ''

                },
                isCrudExists: false,
                driver: 'mysql'
            };
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchDatabaseTables();
        },
        filters: {
          capitalize: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
          }
        },
        computed: {
            items: function(fields) {
              function compare(a, b) {
                if (a.order < b.order)
                  return -1;
                if (a.order > b.order)
                  return 1;
                return 0;
              }
              // Clone Array then sort
              return [...this.fields].sort(compare);
            }
        },
        methods: {
            async fetchDatabaseTables() {

                let res = await this.getData();
                // Set fetched data
                this.userPermissions = res.userPermissions;
                this.relationship.tables = res.relationship_tables;
                this.table = res.object;
                this.fields = res.fields;
                this.isCrudExists = res.isCrudExists;
                this.driver = res.driver;

                for(let field of this.fields) {

                  if(field.settings != null) {
                    // Formated json string with 4 spaces
                    field.settings = JSON.stringify(field.settings, null, 4);
                    continue;
                  }

                  field.settings = '';
                }
                // Relation
                this.foreignTableDetails = res.relationship_details.foreignTableDetails;
                this.localTableDetails = res.relationship_details.localTableDetails;
                this.relationship.type = res.relationship_details.type;
                this.setRelationshipData();
                // Table
                this.setTableData();
                // Remove previous errors
                this.databaseErrors = [];
                // Initialize Nested drag and drop fields
                this.initNestable('#crud_fields');

                this.loadComponent();
                
            },

            getData: function(){
              return axios.get(`/api${this.prefix}/crud/details/${this.tableName}`)
              .then(res => {return res.data})
              .catch(err => this.displayError(err.response));
            },
            addOrEditCrud: function() {
                this.$Progress.start()
                let isCrudExists = this.isCrudExists;
                let object = this.table;
                let fields = this.items;

                axios.post('/api'+this.prefix+'/crud',{isCrudExists, object, fields})
                .then(res => {
                    if( res.data.success == true){
                      // Refresh
                      this.fetchDatabaseTables();
                      this.$Progress.finish()
                      //Load Menu
                      this.$emit('check', 'reloadMenu')
                      // Flash Success message
                      if(this.isCrudExists) {
                        toastr.success("CRUD Edited Successfully.",this.tableName);
                      }else {
                        toastr.success("CRUD Added Successfully.",this.tableName);
                      }
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response);               
                });
            },
            setRelationshipData: function(){
              // Foreign
              this.relationship.foreignTable  = this.foreignTableDetails.name;
              this.relationship.foreignModel  = '';
              if( this.foreignTableDetails.columns.length > 0) {
                this.relationship.displayLabel  = this.foreignTableDetails.columns[0].name;
                this.relationship.foreignFields = this.foreignTableDetails.columns;
                this.relationship.foreignKey    = this.foreignTableDetails.columns[0].name;
              }
              // Local
              this.relationship.localTable    = this.localTableDetails.name;
              this.relationship.localModel    = this.table.model;
              if( this.localTableDetails.columns.length > 0 ) {
                this.relationship.localFields   = this.localTableDetails.columns;
                this.relationship.localKey      = this.localTableDetails.columns[0].name;
              }
              //Pivot
              this.relationship.pivotTable    = this.relationship.tables[0];
            },
            setTableData: function(){

              if(this.isCrudExists && this.table.details != null) {

                this.table.findColumn         = (this.table.details.findColumn) 
                                                ? (this.table.details.findColumn) :
                                                this.localTableDetails.columns[0].name;
                this.table.perPage            = (this.table.details.perPage) 
                                                ? (this.table.details.perPage) :
                                                "";
                this.table.searchColumn       = (this.table.details.searchColumn) 
                                                ? (this.table.details.searchColumn) 
                                                : "";
                this.table.orderColumn        = (this.table.details.orderColumn) 
                                                ? (this.table.details.orderColumn) 
                                                : this.localTableDetails.columns[0].name;
                this.table.orderDisplayColumn = (this.table.details.orderDisplayColumn) 
                                                ? (this.table.details.orderDisplayColumn) 
                                                : "";
                this.table.orderDirection     = (this.table.details.orderDirection) 
                                                ? (this.table.details.orderDirection) 
                                                : "DESC";
              }else if( this.localTableDetails.columns.length > 0 ){

                this.table.findColumn         = this.localTableDetails.columns[0].name;
                this.table.perPage            = "";
                this.table.searchColumn       = "";
                this.table.orderColumn        = this.localTableDetails.columns[0].name;
                this.table.orderDisplayColumn = "";
                this.table.orderDirection     = "DESC";
              }
            },
            initNestable: function(selector = '#nestable'){
                var self = this;
                setTimeout(function(){
                    $(selector).nestable({
                      maxDepth: 1,
                      group: 1,
                      callback: function(l,e){
                        // l is the main container
                        // e is the element that was moved
                        var list   = l.length ? l : $(l.target);
                        var lists = list.nestable('serialize');
                        var order = 1;
                        for(var listItem of lists) {
                            var field = self.fields[listItem.index];
                            field.order = order;
                            order = order + 1;
                        }
                      }
                    });

                },500);
            }
        }
    }
</script>