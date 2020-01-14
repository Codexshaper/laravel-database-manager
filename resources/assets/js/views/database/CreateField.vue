<template>
    <div class="dd field-lists" id="">
      <draggable
        :list="fields"
        class="list-group"
        ghost-class="ghost">
          <div 
            v-for="(field, index) in fields" 
            :key="index" 
            class="dd-item" 
            :data-index="index">
              <div class="dd-handle">{{ field.name }}</div>
              <div class="action-area">
                <a 
                  href="#"  
                  class="btn btn-info cs-all-btn edit-field" 
                  data-toggle="collapse" 
                  :href="'#collapse_'+index+1" 
                  title="edit"> <i class="fas fa-edit"></i></a>
                <a 
                  href="#" 
                  v-on:click.prevent="removeField(index)" 
                  class="btn btn-danger cs-all-btn"  
                  title="delete"><i class="far fa-trash-alt"></i></a>
              </div>
             <!-- ================== collapse  Start ================== -->
              <div class="collapse" :id="'collapse_'+index+1">
                  <div class="card card-body">
                    <form>
                      <div class="row">
                         <div class="col-md-6">
                           <div class="form-group cs-form-group"> 
                              <label for="name" class="cs-label">  Name</label>
                              <input type="text" class="form-control" v-model="field.name">
                           </div>
                         </div>
                         <div class="col-md-6">
                              <div class="form-group cs-form-group"> 
                                 <label class="cs-label">Type</label>
                                 <component 
                                  :is="loadTypeCompnent" 
                                  :field="field" 
                                  :notSupportIndexs="notSupportIndexs" 
                                  :notSupportDefault="notSupportDefault"></component>
                               </div>
                         </div>
                         <div class="col-md-6">
                           <div class="form-group cs-form-group"> 
                              <label for="number" class="cs-label">Length</label>
                              <input type="number" class="form-control" v-model="field.length">
                           </div>
                         </div>
                         <div class="col-md-6">
                           <div class="form-group cs-form-group"> 
                              <label for="number" class="cs-label">index</label>
                              <select 
                                class="form-control" 
                                :disabled=" !checkSupportedIndex(field) || isNotSupportedIndex" 
                                v-model="field.index">
                                <option value="">Select INDEX</option>
                                <option value="INDEX">INDEX</option>
                                <option value="UNIQUE">UNIQUE</option>
                                <option value="PRIMARY" v-if="!isMongoDB">PRIMARY</option>
                                <option value="TEXT" v-if="isMongoDB">TEXT INDEX</option>
                                <option value="ASC" v-if="isMongoDB">ASCENDING</option>
                                <option value="DESC" v-if="isMongoDB">DESCENDING</option>
                                <option value="UNIQUE_DESC" v-if="isMongoDB">UNIQUE DESCENDING</option>
                                <option value="TTL" v-if="isMongoDB">TTL</option>
                                <option value="SPARSE" v-if="isMongoDB">SPARSE</option>
                                <option value="SPARSE_DESC" v-if="isMongoDB">SPARSE DESCENDING</option>
                                <option value="SPARSE_UNIQUE" v-if="isMongoDB">SPARSE UNIQUE</option>
                                <option value="SPARSE_UNIQUE_DESC" v-if="isMongoDB">SPARSE &amp; UNIQUE DESCENDING</option>
                              </select>
                           </div>
                         </div>
                         <div class="col-md-6">
                           <div class="form-group cs-form-group">
                              <input 
                                type="string" 
                                name="default" 
                                :disabled=" !checkSupportedDefault(field) || isNotSupportedDefault " 
                                v-model="field.default" 
                                class="form-control" />
                              <label class="cs-label">Default</label>
                           </div>
                         </div>
                         <div class="col-md-6">
                           <div class="form-group label-group">
                              <label :for="'notnull'+index+1" >
                                <input 
                                  type="checkbox" 
                                  name="notnull" 
                                  v-model="field.notnull" 
                                  :id="'notnull'+index+1" />  Not Null
                              </label> 
                              <label :for="'unsigned'+index+1">
                                <input 
                                  type="checkbox" 
                                  name="unsigned" 
                                  v-model="field.unsigned" 
                                  :id="'unsigned'+index+1" /> Unsigned   
                              </label>
                               <label 
                                :for="'auto_increment'+index+1" 
                                :disabled="(field.autoincrement != true && disableAutoIncrement)"
                               >
                                <input 
                                  type="checkbox" 
                                  name="auto_increment" 
                                  :id="'auto_increment'+index+1" 
                                  :disabled="(field.autoincrement != true && disableAutoIncrement)" 
                                  v-model="field.autoincrement"
                                  @change="checkAutoIncrement" />  Auto Increment 
                              </label>
                           </div>
                         </div>
                      </div>
                    </form>
                  </div>
                </div>
                <!-- ================== collapse  Start ================== -->
          </div>
      </draggable>
  </div>
</template>

<script>

    import Mysql from '../../components/data-types/Mysql.vue';
    import Sqlite from '../../components/data-types/Sqlite.vue';
    import MongoDB from '../../components/data-types/MongoDB.vue';

    import draggable from 'vuedraggable'

    let id = 1;
    export default {
        props: {
          fields: Array,
          templates: Array,
          connection: String,
          notSupportIndexs: Array,
          notSupportDefault: Array,
        },
        name: "simple",
        display: "Simple",
        order: 0,
        components: {
          draggable
        },
        computed: {
          draggingInfo() {
            return this.dragging ? "under drag" : "";
          },
          loadTypeCompnent () {
            switch(this.connection) {
              case 'mysql':
                return () => import('../../components/data-types/Mysql.vue');
                break;
              case 'sqlite':
                return () => import('../../components/data-types/Sqlite.vue');
                break;
              case 'mongodb':
                return () => import('../../components/data-types/MongoDB.vue');
                break;
              default:
                return () => import('../../components/data-types/Mysql.vue');
                break;
            } 
          },
          isNotSupportedIndex:{
            get: function(){

            },
            set: function(value) {
              return value;
            },
          },
          isNotSupportedDefault:{
            get: function(){

            },
            set: function(value) {
              return value;
            }
          },
          isMongoDB: function(){
            return (this.connection == 'mongodb');
          },
          disableAutoIncrement: {
            get: function(){
              for(let field of this.fields) {
                if(field.autoincrement == true) {
                  return true;
                }
              }
              return false;
            },
            set: function(value) {
              return value;
            }
            
          }
        },
        methods: {
            removeField: function(index) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this menu item',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.value) {
                        this.fields.splice(index, 1);
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire(
                            'Cancelled',
                            'Your imaginary file is safe :)',
                            'error'
                        )
                    }
                });
                
            },
            checkAutoIncrement: function(){
              for(let field of this.fields) {
                if(field.autoincrement == true) {
                  this.disableAutoIncrement = true;
                }
              }
              this.disableAutoIncrement = false;
            },
            checkSupportedIndex: function(field){
              if(this.notSupportIndexs.includes(field.type.name)) {
                return false;
              }
              return true;
            },
            checkSupportedDefault: function(field) {
              if(this.notSupportDefault.includes(field.type.name)) {
                return false;
              }
              return true;
            },
        }
    }
</script>