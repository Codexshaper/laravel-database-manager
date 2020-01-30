<template>
    <div class="">
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" 
            :key="key"> {{ databaseError }} </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content"> <!-- cs-content start -->
                <div class="dbase-btn btn-area">
                    <a 
                        v-if="hasPermission('backup.create')" 
                        href="#" 
                        class="btn btn-success" 
                        v-on:click.prevent="backup"> <i class="fas fa-plus"></i>Make Database BackUp</a>
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
                <div class="table-responsive" v-if="files.length > 0">
                    <table 
                        v-if="hasPermission('backup.browse')" 
                        id="database-table" 
                        class="table table-striped table-bordered database-tables backup-table" 
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Last Modified</th>
                                <th
                                    class="action" 
                                    v-if="hasPermission('backup.restore') || 
                                    hasPermission('backup.download') || 
                                    hasPermission('backup.delete')">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(file, index) in files" :key="index" :data-id="index+1">
                                <td>{{ file.info.basename }}</td>
                                <td>{{ file.lastModified }}</td>
                                <td class="action">
                                    <a 
                                        v-if="hasPermission('backup.restore')" 
                                        href="#" 
                                        class="btn btn-backup cs-all-btn" 
                                        v-on:click.prevent="restore(file)">Restore</a>
                                    <a 
                                        v-if="hasPermission('backup.download')" 
                                        href="#" 
                                        class="btn btn-success cs-all-btn" 
                                        v-on:click.prevent="download(file)">Downlaod</a>
                                    <a 
                                        v-if="hasPermission('backup.delete')" 
                                        href="#" 
                                        v-on:click.prevent="deleteBackup(file)" 
                                        class="btn btn-danger cs-all-btn"  
                                        title="delete">Delete</a>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                               <th>Filename</th>
                                <th>Last Modified</th>
                                <th 
                                    class="action" 
                                    v-if="hasPermission('backup.restore') || 
                                    hasPermission('backup.download') || 
                                    hasPermission('backup.delete')">Action</th>
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
                            @pagination-change-page="fetchDatabaseBackups"></pagination>
                    </div>
                </div>
            </div>
        </transition>
    </div><!-- cs-content start -->
</template>

<script>
    export default {
        props: ['prefix'],
        data() {
            return {
                userPermissions: [],
                tableName: "",
                files: [],
                pagination: {},
                perPage: 5,
                search: "",
            };
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchDatabaseBackups();
        },
        methods: {
            fetchDatabaseBackups: function(page=1) {
                axios.get(`/api${this.prefix}/getBackups?page=${page}&perPage=${this.perPage}&q=${this.search}`)
                .then(res => {
                    if( res.data.success == true ){
                        this.files = res.data.files;
                        this.userPermissions = res.data.userPermissions;
                        this.pagination = res.data.pagination;
                        this.databaseErrors = [];
                        this.loadComponent();
                        this.$Progress.finish()
                    }
                })
                .catch(err => {
                    this.$Progress.finish()
                    this.displayError(err.response)
                });
            },
            reload: _.debounce(function() {
                this.fetchDatabaseBackups();
            }, 500),
            backup: function(){
                this.$Progress.start()   
                var url = '/api'+this.prefix+'/backup';
                var self = this;

                axios({
                  method: 'post',
                  url: url,
                  data:{isTable: false},
                  responseType: 'json',
                })
                .then(res => {
                    if( res.data.success == true ){
                        toastr.success('Database BackedUp Successfully.');
                        self.fetchDatabaseBackups();
                        this.$Progress.finish()
                    }
                })
                .catch(err => this.displayError(err.response));
            },
            restore: function(file) {
                this.$Progress.start()
                var url = '/api'+this.prefix+'/backup';
                var self = this;
                axios({
                  url: url,
                  method: 'PUT',
                  params: {
                    path: file.info.dirname+'/'+file.info.basename
                  },
                  responseType: 'json',
                }).then((res) => {
                    if (res.data.success == true) {
                        toastr.success('Database Restored Successfully.');
                        this.databaseErrors = [];
                        this.$Progress.finish()
                    }
                })
                .catch(err => this.displayError(err.response));
            },
            download: function(file) {

                axios.get('/api'+this.prefix+'/download',{
                  params: {
                    path: file.info.dirname+'/'+file.info.basename
                  },
                }).then((res) => {
                    if(res.data.success == true) {
                        const url = window.URL.createObjectURL(new Blob([res.data.file]));
                        const link = document.createElement('a');
                        link.setAttribute('href', url);
                        link.setAttribute('download', file.info.basename);
                        document.body.appendChild(link);
                        link.click();
                    }
                })
                .catch(err => this.displayError(err.response));
            },
            deleteBackup: function(file) {

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
                        axios({
                          url: '/api'+this.prefix+'/backup',
                          method: 'DELETE',
                          params: {
                            path: file.info.dirname+'/'+file.info.basename
                          },
                          responseType: 'json',
                        }).then((res) => {
                            if (res.data.success == true) {
                                toastr.success('Database BackedUp Deleted Successfully.');
                                this.fetchDatabaseBackups();
                                this.$Progress.finish()
                            }
                        })
                        .catch(err => {
                            this.$Progress.fail()
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