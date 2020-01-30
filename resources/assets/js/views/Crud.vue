<template>
    <div class="">
        <div 
            class="database-error alert alert-danger" 
            role="alert" 
            v-for="(databaseError,key) in databaseErrors" 
            :key="key"> {{ databaseError }} </div>
        <transition name="fade" mode="out-in">
            <div v-if="isLoaded" class="vue-content">
                <div class="btn-area">
                    <h2>Database Table lists</h2>
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
                                entries
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="database-table_filter" class="dataTables_filter search-bar text-right">
                            <label>Search:<input type="search" v-model="search" @keyup="reload" @keydown="reload" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" v-if="tables.length > 0">
                    <table 
                        v-if="hasPermission('crud.browse')" 
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
                                <td> {{ table.name }}</td>
                                <td class="action">
                                    <router-link 
                                        v-if="table.isCrud && hasPermission('record.browse')"
                                        :to="{ name: 'record', params:{tableName: table.name} }" 
                                        class="btn btn-backup cs-all-btn">Records</router-link>
                                    <router-link 
                                        v-if="table.isCrud && hasPermission('crud.update')"
                                        :to="{ name: 'crudAddEdit', params:{tableName: table.name} }" 
                                        class="btn btn-info cs-all-btn">Edit CRUD</router-link>
                                    <router-link 
                                        v-if="table.isCrud == false && hasPermission('crud.create')"
                                        :to="{ name: 'crudAddEdit', params:{tableName: table.name} }" 
                                        class="btn btn-success cs-all-btn">Add CRUD</router-link>
                                    <a 
                                        v-if="table.isCrud && hasPermission('crud.delete')" 
                                        href="#" 
                                        v-on:click.prevent="removeCrud(table.name)" 
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
                            @pagination-change-page="fetchCrudTables"></pagination>
                    </div>
                </div>

            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        props: ["prefix"],
        data() {
            return {
                userPermissions: [],
                tableName: "",
                tables: [],
                pagination: {},
                perPage: 5,
                search: ""
            };
        },
        created() {
            toastr.options.closeButton = true;
            axios.defaults.headers.common['Content-Type'] = 'application/json'
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('dbm.authToken');
            this.fetchCrudTables();
        },
        methods: {
            fetchCrudTables: function(page = 1) {
                axios.get(`/api${this.prefix}/crud/tables?page=${page}&perPage=${this.perPage}&q=${this.search}`)
                .then(res => {
                    if( res.data.success == true ){
                        this.tables = res.data.tables;
                        this.userPermissions = res.data.userPermissions;
                        this.pagination = res.data.pagination;
                        this.databaseErrors = [];
                        this.loadComponent();
                    }
                })
                .catch(err => this.displayError(err.response));
            },
            reload: _.debounce(function() {
                this.fetchCrudTables();
            }, 500),
            removeCrud: function(table) {
                let self = this;
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
                        axios.delete('/api'+this.prefix+'/crud/'+table).then(res => {
                            if( res.data.success == true ){
                                // Reload menu
                                this.$emit('check', 'reloadMenu')
                                toastr.success("CRUD Deleted Successfully");
                                self.fetchCrudTables()
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
        }
    }
</script>