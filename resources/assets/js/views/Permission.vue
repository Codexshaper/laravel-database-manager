<template>
    <div>
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" 
            :key="key"> {{ databaseError }} </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content">
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
                <div class="table-responsive">
                    <table id="permission-table" class="table table-bordered permission">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="action">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="index" :data-id="index+1">
                                <td>{{user[user.display_name] }}</td>
                                <td  class="action">
                                    <a 
                                        v-if="user.permissions.length > 0"
                                        v-on:click.prevent="viewUserPermissions(user)" 
                                        href="#" 
                                        class="btn btn-view cs-all-btn" 
                                        data-toggle="modal" 
                                        data-target="#viewformModal">View</a>
                                    <a 
                                        v-if="user.permissions.length > 0"
                                        v-on:click.prevent="editUserPermissions(user)" 
                                        href="#" 
                                        class="btn btn-info cs-all-btn"  
                                        data-toggle="modal" 
                                        data-target="#addEditPermissionsModal">Edit</a>
                                    <a 
                                        v-if="user.permissions.length > 0"
                                        v-on:click.prevent="removeUserPermissions(user)" 
                                        href="#" class="btn btn-danger cs-all-btn">Delete</a>
                                    <a 
                                        v-if="user.permissions.length <= 0"
                                        v-on:click.prevent="showPermissionModal(user)"
                                        href="#" 
                                        class="btn btn-success cs-all-btn"  
                                        data-toggle="modal" 
                                        data-target="#addEditPermissionsModal">Add</a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>User Name</th>
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
                            @pagination-change-page="fetchDatabasePermissions"></pagination>
                    </div>
                </div>

                <!-- View User Permissions -->
                 <div class="modal fade" id="viewformModal" v-if="isRender">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h4 class="modal-title font-weight-bold">Permission View</h4>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <div class="row permission-scroll">
                                <div  v-for="(permission,category) in permissions" :key="category" class="col-md-6 permission-available">
                                    <div class="permission-view-details">
                                        <h3 class="title">{{ category }}</h3>
                                        <ul class="permission-list">
                                            <li :data-privilege="privilege" v-for="privilege in userPrevileges[category]" :key="privilege.id"><span>{{ privilege.slug }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>

                <!--  create form modal -->
                <div class="modal fade" id="addEditPermissionsModal" tabindex="-1" role="dialog" aria-labelledby="addEditPermissionsModalTitle"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title font-weight-bold">{{ action == 'add' ? 'Assign' : 'Update' }} Permission</h4>
                            <button type="button" style="color:#fff" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form v-on:submit.prevent="action == 'edit' ? updateUserPermissions(privileges) : addUserPermissions(privileges)">
                                <div class="permission-available">
                                    <label class="checkbox" for="add_select_all">
                                        Select All
                                        <input type="checkbox" name="all_privileges" id="add_select_all" v-model="selectAll">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="row permission-scroll">
                                    <div  v-for="(permission,category) in permissions" :key="category" class="col-md-6 permission-available">
                                        <h3 class="title">{{ category }}</h3>
                                        <div v-for="(privilege, index) in permission" :key="privilege.id">
                                            <label class="checkbox" :for="category+'_'+privilege.slug+'_'+index">
                                                {{ privilege.slug }}
                                                <input 
                                                    type="checkbox" 
                                                    :name="category+'_'+privilege.slug+'_'+index" 
                                                    :id="category+'_'+privilege.slug+'_'+index" 
                                                    v-model="privileges" 
                                                    :value="privilege.id">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-footer text-right">
                                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">{{ action == 'edit' ? 'Update' : 'Add' }}</button>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ['prefix'],
        data() {
            return {
                action: 'add',
                tableName: "",
                permissions: [],
                userPrevileges: {},
                privileges: [],
                all_privileges: [],
                users: [],
                user: {},
                pagination: {},
                perPage: 5,
                search: "",
                isRender: false
            };
        },
        computed: {
            selectAll: {
                get () {
                    return this.all_privileges.length == this.privileges.length
                },
                set (value) {
                    this.selectAllPrevelege(value);
                }
                
            }
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchDatabasePermissions();
        },
        methods: {
            fetchDatabasePermissions: function(page=1) {

                let url = `/api${this.prefix}/permissions?page=${page}&perPage=${this.perPage}&q=${this.search}`;
                let self = this;

                axios({
                  method: 'get',
                  url: url,
                  responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        this.permissions = res.data.permissions;
                        this.all_privileges = res.data.privileges;
                        this.users = res.data.pagination.data;
                        this.pagination = res.data.pagination;
                        this.databaseErrors = [];

                        console.log(this.all_privileges);

                        this.loadComponent();
                    }
                    
                })
                .catch(err => this.displayError(err.response));
            },
            reload: _.debounce(function() {
                this.fetchDatabasePermissions();
            }, 500),
            viewUserPermissions: function(user){
                this.isRender = false;
                this.user = user;
                let userPermissions = user.permissions;

                for(let category in this.permissions) {
                    let privileges = [];
                    for(let permission of userPermissions) {
                        if(permission.prefix == category) {
                            privileges.push(permission);
                        }
                    }
                    this.userPrevileges[category] = privileges;
                }

                this.isRender = true;
            },
            showPermissionModal: function(user){
                this.user = user;
                this.privileges = [];
                this.action = 'add';
            },
            addUserPermissions: function(privileges){
                this.$Progress.start()
                let url = '/api'+this.prefix+'/permissions/assignUserPermissions';
                let self = this;

                axios({
                    method: 'post',
                    url: url,
                    data: {
                        privileges: privileges,
                        user: this.user
                    },
                    responseType: 'json',
                }).then(res => {
                    if( res.data.success == true ){
                        toastr.success("Permission assigned successfully.");
                        self.fetchDatabasePermissions();
                        self.resetForm();
                        self.closeModal();
                        this.$Progress.finish()
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response)
                });
            },
            editUserPermissions: function(user){
                this.action = 'edit';
                this.user = user;
                let permissions = user.permissions;
                this.privileges = [];

                for(let permission of permissions) {
                    this.privileges.push(permission.id);
                }
            },
            updateUserPermissions: function(privileges) {
                this.$Progress.start()
                let url = '/api'+this.prefix+'/permissions/syncUserPermissions';
                let self = this;

                axios({
                    method: 'put',
                    url: url,
                    data: {
                        privileges: privileges,
                        user: this.user
                    },
                    responseType: 'json',
                }).then(res => {
                    if( res.data.success == true ){
                        toastr.success("Permission Updated successfully.");
                        self.fetchDatabasePermissions();
                        self.resetForm();
                        self.closeModal();
                        this.$Progress.finish()
                    }
                    
                })
                .catch(err => {
                    this.$Progress.fail()
                    this.displayError(err.response)
                });
            },
            removeUserPermissions: function(user){
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
                        var url = '/api'+this.prefix+'/permissions/deleteUserPermissions';
                        
                        axios({
                          method: 'delete',
                          url: url,
                          params: {
                            user: user
                          },
                          responseType: 'json',
                        })
                        .then(res => {
                            if( res.data.success == true ){
                                toastr.success("User Permiussion Deleted Successfully");
                                self.fetchDatabasePermissions();
                                this.$Progress.finish()
                            }
                            
                        })
                        .catch(err => {
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
            selectAllPrevelege: function(value){
                this.privileges = [];
                if(value == true) {
                    let permissions = this.permissions;
                    for(let privileges in permissions) {
                        for(let privilege of permissions[privileges]) {
                            this.privileges.push(privilege.id);
                        }
                    }
                }
            },
            getUserPermissions: function(user) {

                let html = '<ul>';
                for(let permission of user.permissions) {

                    html += '<li>'+permission.name+' '+permission.prefix+'</li>';
                }
                html += '</ul>';

                return html;
            },
            resetForm: function(){
                this.privileges= [];
                this.databaseErrors = [];
            },
            closeModal: function(){
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
            },
        }
    }
</script>