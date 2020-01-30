<template>
  <div id="field_table" class="panel-collapse collapse show">
    <div class="crud-btn-area">
      <p>{{ tableName | capitalize }} Fields</p>
      <button 
          v-if="isCrudExists && hasPermission('crud.update')" 
          v-on:click.prevent="showAddRelationForm"  
          class="btn btn-relationship" 
          data-toggle="modal" 
          data-target="#relationshipModal">Make a Relation</button>
    </div>
    <div class="panel-body">
      <div class="row cs-table-head">
          <div class="head-type">Field</div>
          <div class="head-type">Permissions</div>
          <div class="head-type">Type</div>
          <div class="head-type h-display">Display Name</div>
          <div class="head-type h-function" v-if="driver == 'mysql'">Function</div>
          <div class="head-type h-setting">Settings</div>
      </div>
      <div class="cs-item">
          <div class="dd" id="crud_fields">
            <ol class="dd-list">
                <li 
                  v-for="(field, index) in fields" 
                  :key="getId(field)" 
                  class="dd-item dd3-item"
                  :data-index="index" 
                  :data-id="getId(field)" 
                  :data-order="field.order">
                  <!-- Handle for sort -->
                  <div class="dd-handle dd3-handle"><i class="fas fa-arrows-alt"></i></div>
                  <!-- Item Container -->
                  <div class="dd3-content">
                    <!-- Content for relationship fields -->
                    <div v-if="field.type == 'relationship'" class="row row-dd">
                      <div class="filed_id">
                          <h4><strong>{{ field.display_name }}</strong></h4>
                      </div>
                      <div class="col-sm-2 permisson-check">
                            <label :for="'create_permission_'+(index+1)">
                              <input 
                              type="checkbox" 
                              v-model="field.create" 
                              :id="'create_permission_'+(index+1)"> Create
                            </label>
                            <label :for="'read_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.read" 
                                :id="'read_permission_'+(index+1)"> Read
                            </label>
                            <label :for="'edit_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.edit" 
                                :id="'edit_permission_'+(index+1)"> Edit
                            </label>
                            <label :for="'delete_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.delete" 
                                :id="'delete_permission_'+(index+1)"> Delete
                            </label>
                      </div>

                      <div class="col-sm-2 field-type">
                         <p>{{ field.type }}</p>
                      </div>

                      <div class="col-sm-4 display-name">
                        <input type="text" class="form-control" v-model="field.display_name">
                      </div>
                      <div class="col-sm-3 field-setting text-right">
                        <button 
                          class="btn btn-info cs-all-btn" 
                          data-toggle="modal" 
                          data-target="#relationshipModal" 
                          v-on:click.prevent="editRelation(index)">Edit</button>
                        <button 
                          class="btn btn-danger cs-all-btn" 
                          v-on:click.prevent="deleteRelation(index)">Delete</button>
                      </div>
                    </div>
                    <!-- Content for normal fields -->
                    <div class="row row-dd" v-else>
                      <div class="filed_id">
                          <h4><strong>{{ field.display_name }}</strong></h4>
                      </div>
                      <div class="col-sm-2 permisson-check">
                            <label :for="'create_permission_'+(index+1)">
                              <input 
                              type="checkbox" 
                              v-model="field.create" 
                              :id="'create_permission_'+(index+1)"> Create
                            </label>
                            <label :for="'read_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.read" 
                                :id="'read_permission_'+(index+1)"> Read
                            </label>
                            <label :for="'edit_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.edit" 
                                :id="'edit_permission_'+(index+1)"> Edit
                            </label>
                            <label :for="'delete_permission_'+(index+1)">
                              <input 
                                type="checkbox" 
                                v-model="field.delete" 
                                :id="'delete_permission_'+(index+1)"> Delete
                            </label>
                      </div>

                      <div class="col-sm-2 field-type">
                         <input-fields :field="field"></input-fields>
                      </div>
                      <div class="col-sm-2 display-name">
                          <input type="text" class="form-control" v-model="field.display_name">
                      </div>

                      <div class="col-sm-2 field-function" v-if="driver == 'mysql'">
                        <mysql-functions :field="field"></mysql-functions>
                      </div>

                      <div class="col-sm-3 field-setting">
                        <a href="#" class="btn-link field-setting-link" data-toggle="collapse" :data-target="'#'+field.name"
                        :class="{active: field.isSettingsActive}" @click="fireSettingsToggle(field)">
                          {{ field.isSettingsActive ? 'close' : 'open' }} settings <i class="fas " :class="field.isSettingsActive ? 'fa-angle-up' : 'fa-angle-down'"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="collapse" :id="field.name">
                    <div class="card card-body">
                      <textarea v-model="field.settings" class="field-setting-input"></textarea>
                    </div>
                  </div>
                </li>
            </ol>
        </div><!-- End Items -->
    </div>
  </div>

  <database-crud-modals
      v-if="isCrudExists && hasPermission('crud.update')"
      :action = "action"
      :userPermissions = "userPermissions"
      :relationship = "relationship"
      :changeRelationshipType = "changeRelationshipType"
      :changeForeignTable = "changeForeignTable"
      :changePivotTable = "changePivotTable"
      :addRelation = "addRelation"
      :updateRelation = "updateRelation"></database-crud-modals>

</div>
</template>

<script>

    import DatabaseCrudModals from '../../components/modals/DatabaseCrudModals.vue';
    import InputFields from '../partials/InputFields.vue';
    import MysqlFunctions from '../partials/MysqlFunctions.vue';

    export default {
        props: {
          tableName: String,
          fields: Array,
          driver: String,
          prefix: String,
          isCrudExists: Boolean,
          relationship: Object,
          fetchDatabaseTables: Function,
          userPermissions: Array,
        },
        components: {
          DatabaseCrudModals,
          MysqlFunctions,
          InputFields,
        },
        data() {
            return {
              currentIndex: 0,
              action: 'add',
            };
        },
        created(){
          for(let field of this.fields) {
            if(field.isSettingsActive == undefined) {
              field.isSettingsActive = false;
            }
            field.create  = field.create == 1;
            field.read    = field.read == 1;
            field.edit    = field.edit == 1;
            field.delete  = field.delete == 1;
          }
        },
        filters: {
          capitalize: function (value) {
            if (!value) return ''
            value = value.toString()
            return value.charAt(0).toUpperCase() + value.slice(1)
          }
        },
        methods: {
          getId: function(field){
              return (this.driver == 'mongodb') ? field._id : field.id;
          },
          showAddRelationForm: function(){
            this.action = 'add';
            this.fetchDatabaseTables();
          },
          addRelation: function(){
              this.$Progress.start()
              var relationship = this.relationship;

              axios.post('/api'+this.prefix+'/relationship',{relationship})
              .then(res => {
                  if( res.data.success == true ){
                    toastr.success("Relation Added successfully.",this.tableName);
                    this.fetchDatabaseTables();
                    this.closeModal();
                    this.$Progress.finish()
                  }
                  
              })
              .catch(err => {
                  this.$Progress.fail()
                  this.displayError(err.response);               
              });
          },
          editRelation: function(index) {
            this.currentIndex = index;
            this.action = 'edit';
            var field = this.fields[this.currentIndex];

            axios.get('/api'+this.prefix+'/relationship',{
                params: {
                  table: this.tableName,
                  field: field
                }
              })
              .then(res => {
                  if( res.data.success == true ){

                    var field = res.data.field;

                    this.relationship.type                = field.relationship.relationType;
                    this.relationship.foreignTable        = field.relationship.foreignTable;
                    this.relationship.foreignModel        = field.relationship.foreignModel;
                    this.relationship.pivotTable          = field.relationship.pivotTable;
                    this.relationship.localKey            = field.relationship.localKey;
                    this.relationship.foreignKey          = field.relationship.foreignKey;
                    this.relationship.parentPivotKey      = field.relationship.parentPivotKey;
                    this.relationship.relatedPivotKey     = field.relationship.relatedPivotKey;
                    this.relationship.displayLabel        = field.relationship.displayLabel;

                    this.relationship.localFields         = field.localFields.columns;
                    this.relationship.foreignFields       = field.foreignFields.columns;
                    this.relationship.belongsToManyFields = field.pivotFields.columns;
                  }
                  
              })
              .catch(err => this.displayError(err.response));
          },
          updateRelation: function(){
            this.$Progress.start()
            let field = this.fields[this.currentIndex];
            let relationship = this.relationship

            axios.put('/api'+this.prefix+'/relationship', {field,relationship})
            .then(res => {
                if( res.data.success == true ){
                  toastr.success("CRUD Update successfully.",this.tableName);
                  this.fetchDatabaseTables();
                  this.closeModal();
                  this.$Progress.finish()
                }
                
            })
            .catch(err => {
                this.$Progress.fail()
                this.displayError(err.response);

            });
          },
          deleteRelation: function(index) {
           
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this menu item',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.value) {
                    var field = this.fields[index];
                    
                    axios.delete('/api'+this.prefix+'/relationship', {
                      params: {
                        table: this.tableName,
                        field: field,
                      }
                    })
                    .then(res => {
                        if( res.data.success == true ){
                            toastr.success("Relation Deleted Successfully");
                            this.fetchDatabaseTables();
                        }
                    })
                    .catch(err => {
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
          changeRelationshipType: function(){

            if(this.relationship.type == 'belongsTo') {
              this.getTableColumns(this.relationship.foreignTable).then(data => {
                 this.relationship.foreignFields = data;
              });
            } else if(this.relationship.type == 'belongsToMany') {
              this.getTableColumns(this.relationship.pivotTable).then(data => {
                 this.relationship.belongsToManyFields = data;
              });
            }
          },
          changeForeignTable: function(event){
            this.getTableColumns(event.target.value).then(res => {
              this.relationship.foreignFields = res.data.fields;
            });
          },
          changePivotTable: function(event) {
            this.getTableColumns(this.relationship.pivotTable).then(res => {
              this.relationship.belongsToManyFields = res.data.fields;
            });
          },
          getTableColumns: function(table) {
              return axios.get(`/api${this.prefix}/table/columns/${table}`)
          },
          fireSettingsToggle: function(field){
            let isSettingsActive = field.isSettingsActive;
            Vue.delete(field, 'isSettingsActive');
            if(isSettingsActive) {
              field.isSettingsActive = false;
            }else {
              field.isSettingsActive = true;
            }
          },
          resetForm: function(){
              this.relationship= {};
          },
          closeModal: function(){
              $('.modal').modal('hide');
              $('.modal-backdrop').remove();
          },
            
        }
    }
</script>
<style>
  .field-setting {
        text-align: center;
    }
  .card {
    background: -webkit-linear-gradient(#fafafa 0%, #eee 100%);
    background: -o-linear-gradient(#fafafa 0%, #eee 100%);
    background: linear-gradient(#fafafa 0%, #eee 100%);
    margin-top: -6px;
    border-top: 0px;
    padding: 0px;
  }
  .field-setting-link {
    color: #676a6d;
  }
  .field-setting-input {
    padding: 15px;
    border: 0px;
  }
</style>