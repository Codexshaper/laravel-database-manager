<template>
  <div class="">
    <!-- <div v-if="!isLoaded" class="loading">Loading</div> -->
    <div 
        class="database-error alert alert-danger" 
        role="alert" 
        v-for="(databaseError,key) in databaseErrors" 
        :key="key"> {{ databaseError }} </div>
    <transition name="fade" mode="out-in">
      <div v-if="isLoaded" class="vue-content create-table-area">
        <form action="" method="post" v-on:submit.prevent="updateTable(tableName)" v-if="hasPermission('database.update')">  <!--=============== Form Start ============ -->
          <div class="row">
            <div class="col-sm-4">
              <input type="text" class="form-control" v-model="tableName" placeholder="Table name">
            </div>
            <div class="col-sm-8 text-right">
              <button type="button" v-on:click="showAddFieldForm()" class="btn btn-success create-btn" data-toggle="modal" data-target="#modalcreateForm"><i class="fas fa-plus"></i>created  new Fields</button>
            </div>
            <!-- <div class="col-sm-6 text-right">
               <div class="search">
                <input type="text" class="form-control" placeholder=" search field...">
                <button><i class="fas fa-search"></i></button>
               </div>
            </div> -->
          </div>
          <!--============= database_fields Start============= -->
          <!-- Create Field -->
          <table-builder
              :fields="fields"
              :templates="templates"
              :connection="driver"
              :notSupportIndexs="notSupportIndexs"
              :notSupportDefault="notSupportDefault"
              :disableAutoIncrement="disableAutoIncrement"
          ></table-builder>
          <!-- ======== Pagination ======== -->
          <!-- <nav aria-label="Page navigation">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="#">Previous</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
          </nav> -->
           <!-- ======== Pagination ======== -->

           <!--============= database_fields End============= -->
            <div class="m-footer pull-right">
              <button type="submit" class="btn btn-info">Update</button>
            </div>
        </form><!--=============== Form End ============ -->

         <!-- Modals -->
        <table-builder-modals
          v-if="hasPermission('database.update')"
          :field="field"
          :ids="ids"
          :fields="fields"
          :templates="templates"
          :connection="driver"
          :notSupportIndexs="notSupportIndexs"
          :notSupportDefault="notSupportDefault"
          :disableAutoIncrement="disableAutoIncrement"
          :save-template="saveTemplate"
          :remove-template="removeTemplate"
        ></table-builder-modals>
      </div>
    </transition>
  </div>
</template>

<script>
    import TableBuilderModals from '../../components/modals/TableBuilderModals.vue';
    import tableBuilder from './CreateField.vue';

    export default {
        props: ["name","prefix", "driver"],
        components: {
            TableBuilderModals,
            tableBuilder
        },
        data() {
            return {
                isRenderComponent: false,
                isCrudExists: false,
                userPermissions: [],
                tableName: (this.name != "" || this.name != undefined) ? this.name : "",
                oldTable: this.name,
                field: {},
                fields: [],
                templates: [],
                notSupportIndexs: [
                    'tinytext',
                    'mediumtext',
                    'longtext',
                    'text',
                    'tinyblob',
                    'mediumblob',
                    'blob',
                    'longblob'
                ],
                notSupportDefault: [
                    'tinytext',
                    'mediumtext',
                    'longtext',
                    'text',
                    'tinyblob',
                    'mediumblob',
                    'blob',
                    'longblob',
                    'json'
                ],
                primaryKeyName: "PRIMARY",
                foreignKeys: [],
                options: [],
                ids: [1],
                orders: [1],
                disableAutoIncrement: false,
            };
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchTableFields();
        },
        methods: {
            fetchTableFields: function() {

              axios.get('/api'+this.prefix+'/table/'+this.tableName)
              .then(res => {
                  if( res.data.success == true ){
                    // Set values
                    this.userPermissions = res.data.userPermissions;
                    this.oldTable = res.data.table.oldName;
                    this.fields = res.data.table.columns;
                    this.indexes = res.data.table.indexes;
                    this.foreignKeys = res.data.table.foreignKeys;
                    this.options = res.data.table.options;
                    this.isCrudExists = res.data.isCrudExists;
                    //Templates
                    this.templates = res.data.templates;
                    for(var field of this.fields) {
                      field.index =  this.getIndex(field);
                    }
                    // Set defaults
                    this.databaseErrors = [];
                    this.resetForm();

                    this.loadComponent();
                  }
              })
              .catch(err => this.displayError(err.response));
            },
            updateTable: function(tableName) {

                var order = 1;
                for( var field of this.fields) {
                  field.order = order;
                  order = order + 1;
                }

                let columns = this.prepareColumns(this.fields);
                let indexes = this.prepareIndexes(this.fields);
                let table = JSON.stringify({
                    name: tableName,
                    oldName: this.oldTable,
                    columns: columns,
                    indexes: indexes,
                    primaryKeyName: this.primaryKeyName,
                    foreignKeys: this.foreignKeys,
                    options: this.options
                });
                this.$Progress.start();
                let url = '/api'+this.prefix+'/table';
                let self = this;

                axios({
                  method: 'put',
                  url: url,
                  data: {
                    table: table,
                    templates: this.templates
                  },
                  responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        toastr.success('Table Updated Successfully.');
                        if(this.oldTable != this.tableName) {
                          this.$router.replace({name: 'builder', params:{name: tableName}});
                        }
                        self.fetchTableFields();
                        this.$Progress.finish()
                    }
                    
                })
                .catch(err => {
                  this.$Progress.fail()
                  this.displayError(err.response)
                });
            },
            prepareColumns: function(fields) {
                let columns = [];
                for( var field of fields ) {
                    let column = {
                        name: field.name,
                        oldName: field.oldName ? field.oldName : "",
                        type: field.type,
                        length: field.length ? field.length : this.getDefaultLength(field),
                        fixed: false,
                        unsigned: field.unsigned ? field.unsigned : false,
                        autoincrement: field.autoincrement ? field.autoincrement : false,
                        notnull: field.notnull ? field.notnull : false,
                        default: field.default ? field.default :  null,
                        order: field.order
                    };

                    columns.push(column);
                }

                return columns;
            },
            prepareIndexes: function() {
              this.indexes = [];
              for(let item of this.fields) {
                if( item.index != undefined &&  item.index.length > 0) {
                    this.setIndex(item);
                }
              }
              return this.indexes;
            },
            showAddFieldForm: function() {
                this.resetForm();
                this.checkAutoIncrement();
            },
            saveTemplate: function(field) {

              axios.post('/api'+this.prefix+'/template', {template: field})
              .then(res =>{
                if(res.data.success == true) {
                  this.templates = res.data.templates;
                  // Switch tab
                  $(".tab-pane").removeClass("show active");
                  $("#new_filed_link").removeClass("active");
                  $('#templates').addClass("show active");
                  $('#templates_link').addClass("active");
                  // Flash success message
                  toastr.success(field.name +' Added Successfully into template.');
                }
              })
              .catch(err => this.displayError(err.response));
              
            },
            removeTemplate: function(field){
              axios.delete('/api'+this.prefix+'/template',{params: {name: field.name}})
              .then(res =>{
                if(res.data.success == true) {
                  this.templates = res.data.templates;
                  toastr.success(' Template Deleted Successfully.');
                }
              })
              .catch(err => this.displayError(err.response));
              
            },
            getIndex: function(field) {
              for(var index of this.indexes) {
                  var columns = index.columns;
                  if(columns.includes(field.name)) {
                      return index.type;
                  }
              }
              return "";
            },
            checkAutoIncrement: function() {
                var countAutoIncrementColumns = 0;
                this.disableAutoIncrement = false;
                for(var field of this.fields) {
                    if( field.autoincrement == true ) {
                        countAutoIncrementColumns++;
                    }
                }

                if(countAutoIncrementColumns >= 1) {
                  this.disableAutoIncrement = true;
                }

                return (countAutoIncrementColumns > 1) ? true : false;
            },
            setIndex: function(field) {
                if(this.notSupportIndexs.includes(field.type.name)) {
                    return null;
                }
                let index = {};
                index.columns = [field.name];
                index.type = field.index;
                index.name = "";
                index.table = this.tableName;

                if(field.index == 'PRIMARY') {
                    index.name = 'primary';
                    index.oldName = 'primary';
                    index.isPrimary = true;
                    index.isUnique = true;
                    index.isComposite = false;
                    index.flags = [];
                    index.options = [];
                }

                this.indexes.push(index);
            },
            getDefaultLength: function(field){
              switch(field.type.name) {
                case 'varchar':
                  return 191;
                  break;
                case 'text':
                  return null;
                  break;
              }
            },
            resetForm: function(){
                this.field = {
                    name: "",
                    oldName: "",
                    type: {
                        name: "varchar",
                    },
                    length: null,
                    index: "",
                    default: null,
                    notnull: false,
                    unsigned: false,
                    autoincrement: false,
                };
            }
        }
    }
</script>
