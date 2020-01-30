<template>
    <div class="">
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" 
            :key="key"> {{ databaseError }} </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content">
                <div class="btn-area"">
                    <a 
                        v-if="hasPermission('database.create')" 
                        href="#" 
                        class="btn btn-success" 
                        @click.prevent="showCreateForm()" 
                        data-toggle="modal" 
                        data-target="#createTableModal"> <i class="fas fa-plus"></i>Create new table</a>
                    <router-link 
                        :to="{ name: 'backup' }"
                        v-if="hasPermission('backup.browse')" 
                        class="btn btn-backup">BackUps</router-link>
                    <router-link 
                        :to="{ name: 'crud' }"
                        v-if="hasPermission('crud.browse')" 
                        class="btn btn-info">Cruds</router-link>
                </div>
                <div class="row search-area">
                    <div class="col-sm-12 col-md-6">
                        <div class="dataTables_length" id="database-table_length">
                            <label>Show 
                                <select v-model="perPage" @change="reload" class="custom-select custom-select-sm form-control form-control-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select> 
                            entries</label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="database-table_filter" class="dataTables_filter search-bar text-right">
                            <label>Search:<input type="search" v-model="search" @keyup="reload" @keydown="reload" class="form-control form-control-sm" placeholder="" aria-controls="database-table"></label>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" v-if="tables.length > 0">
                    <table 
                        v-if="hasPermission('database.browse')" 
                        id="database-table" 
                        class="table table-striped table-bordered database-tables" 
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Table Name</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(table, index) in tables" :key="index" :data-id="index+1">
                                <td> {{ table }}</td>
                                <td class="action">
                                    <a 
                                        v-if="hasPermission('database.browse')"
                                        v-on:click.prevent="viewTableFields(table)" 
                                        href="#"
                                        class="btn btn-view cs-all-btn"
                                        data-toggle="modal" 
                                        data-target="#viewDatabaseModal"
                                        aria-hidden="true">View</a>
                                    <a 
                                        v-if="hasPermission('backup.create')"
                                        v-on:click.prevent="backupTable(table)" 
                                        href="#" 
                                        class="btn btn-backup cs-all-btn" >Backup</a>
                                    <router-link 
                                        :to="{ name: 'builder', params: {name: table} }" 
                                        class="btn btn-info cs-all-btn" 
                                        v-if="hasPermission('database.update') && checkCoreTablePermission(table)">Builder</router-link>
                                    <a 
                                        v-if="hasPermission('database.delete') && checkCoreTablePermission(table)"
                                        v-on:click.prevent="removeTable(table)" 
                                        href="#"  
                                        class="btn btn-danger cs-all-btn"  
                                        title="delete">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <th>Table Name</th>
                                <th class="action">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" id="database-table_info" role="status" aria-live="polite">Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries</div>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <pagination 
                            :data="pagination" 
                            :limit="2" 
                            align="right" 
                            @pagination-change-page="fetchDatabaseTables"></pagination>
                    </div>
                </div>

                <database-modals
                    v-if="isModalComponent"
                    :category="category"
                    :tableName="tableName"
                    :collation="collation"
                    :charset="charset"
                    :fields="fields"
                    :connection="driver"
                    :userPermissions="userPermissions"
                    :create-table="createTable"></database-modals>

            </div>
        </transition>
    </div>
</template>

<script>
    import DatabaseModals from '../components/modals/DatabaseModals.vue';
    export default {
        props: ['prefix','driver'],
        components: {
            DatabaseModals,
        },
        data() {
            return {
                isModalComponent: true,
                userPermissions: [],
                category: "",
                tableName: "",
                collation: "utf8mb4_unicode_ci",
                charset: "utf8mb4",
                tables: [],
                pagination: {},
                perPage: 5,
                search: "",
                coreTables: [
                    "dbm_objects",
                    "dbm_fields",
                    "dbm_permissions",
                    "dbm_user_permissions",
                    "dbm_collection_fields",
                    "dbm_collections",
                    "dbm_templates"
                ],
                fields: [],
                indexes: [],
                primaryKeyName: "primary",
                foreignKeys: [],
                options: [],
            };
        },
        created() {
            toastr.options.closeButton = true;
        },
        beforeMount(){
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
        },
        mounted() {
            this.fetchDatabaseTables();
            this.setDefaultFields();
        },
        methods: {
            fetchDatabaseTables: function(page=1) {
                axios.get(`/api${this.prefix}/tables?page=${page}&perPage=${this.perPage}&q=${this.search}`)
                .then(res => {
                    if( res.data.success == true ){
                        this.tables = res.data.tables;
                        this.coreTables = res.data.coreTables;
                        this.collation = res.data.collation;
                        this.pagination = res.data.pagination;
                        this.userPermissions = res.data.userPermissions;
                        this.databaseErrors = [];
                        this.loadComponent();
                    } 
                })
                .catch(err => this.displayError(err.response));
            },
            reload: _.debounce(function() {
                this.fetchDatabaseTables();
            }, 500),
            viewTableFields: function(tableName){
                this.category = tableName;
                axios.get('/api'+this.prefix+'/table/'+tableName)
                .then(res => {
                    if( res.data.success == true ){
                      // Set values
                      this.fields = res.data.table.columns;
                      this.indexes = res.data.table.indexes;

                      for(var field of this.fields) {
                        field.index =  this.getIndex(field);
                      }
                      // Set defaults
                      this.databaseErrors = [];
                      this.resetForm();
                    }
                })
                .catch(err => this.displayError(err.response));
            },
            showCreateForm: function(){
                this.isModalComponent = false;
                this.tableName = '';
                this.setDefaultFields();
                this.forceToRender();
            },
            createTable: function(table) {
                this.options = {
                    charset:table.charset,
                    collation:table.collation
                };
                let columns = this.prepareColumns(this.fields);
                let indexes = this.prepareIndexes(this.fields);
                let tableData = JSON.stringify({
                    name: table.name,
                    oldName: "",
                    columns: columns,
                    indexes: indexes,
                    primaryKeyName: this.primaryKeyName,
                    foreignKeys: this.foreignKeys,
                    options: this.options
                });

                this.$Progress.start()
                let url = '/api'+this.prefix+'/table';
                let self = this;

                axios({
                  method: 'post',
                  url: url,
                  data: {
                    table: tableData
                  },
                  responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        toastr.success('Table Created Successfully.');
                        self.fetchDatabaseTables();
                        self.resetForm();
                        self.closeModal();
                        this.$Progress.finish()
                    }
                })
                .catch(err => {
                    this.$Progress.start()
                    this.displayError(err.response)
                });
            },
            removeTable: function(tableName) {
                let self = this;
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this database',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        this.$Progress.start()
                        axios.delete('/api'+this.prefix+'/table',{
                          params: { table: tableName },
                        }).then(res => {
                            if( res.data.success == true ){
                                toastr.success("Table Deleted Successfully");
                                this.search = "";
                                self.fetchDatabaseTables();
                                this.$Progress.finish()
                            }
                        }).catch(err => {
                            this.$Progress.start()
                            this.displayError(err.response)
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
            getIndex: function(field) {
              for(var index of this.indexes) {
                  var columns = index.columns;
                  if(columns.includes(field.name)) {
                      return index.type;
                  }
              }
              return "";
            },
            prepareColumns: function(fields) {
                let columns = [];
                for( var field of fields ) {
                    let column = {
                        name: field.name,
                        oldName: "",
                        type: field.type,
                        length: field.length,
                        fixed: false,
                        unsigned: field.unsigned,
                        autoincrement: field.autoincrement,
                        notnull: field.notnull,
                        default: field.default,
                        order: field.order
                    };

                    columns.push(column);
                }

                return columns;
            },
            prepareIndexes: function(fields) {
                let indexes = [];
                for( var field of fields ) {
                    let index = field.indexes;

                    if( index != undefined  || index != null) {
                        index.table = this.tableName;
                        indexes.push(index);
                    }
                }

                return indexes;
            },
            setDefaultFields: function(){
                this.fields = [];
                var field = {
                       name: "id",
                       oldName: "",
                       type: {
                          name: "integer",
                       },
                       default: null,
                       notnull: true,
                       length: null,
                       precision: 10,
                       scale: 0,
                       fixed: false,
                       unsigned: true,
                       autoincrement: true,
                       columnDefinition: null,
                       comment: null,
                       null: "NO",
                       extra: "auto_increment",
                       composite: false,
                       index: "PRIMARY",
                       id: 1,
                       order: 1,
                       indexes: {
                            name: "primary",
                            oldName: "primary",
                            columns: ["id"],
                            type: "PRIMARY",
                            isPrimary: true,
                            isUnique: true,
                            isComposite: false,
                            flags: [],
                            options: [],
                            table: ""
                        }
                    };

                this.fields.push(field);
            },
            checkCoreTablePermission(tableName) {
                return !this.coreTables.includes(tableName);
            },
            backupTable: function(table){
                this.$Progress.start()
                var url = '/api'+this.prefix+'/backup';
                var self = this;

                axios({
                  method: 'post',
                  url: url,
                  data:{
                    isTable: true,
                    table: table
                  },
                  responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        toastr.success('Table BackedUp Successfully.');
                        this.$Progress.finish()
                    }
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response)
                });
            },
            forceToRender() {
                this.$nextTick(() => {
                  // Add the component back in
                  this.isModalComponent = true;
                });
            },
            resetForm: function(){
                this.tableName= "";
                this.databaseErrors = [];
            },
            closeModal: function(){
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>