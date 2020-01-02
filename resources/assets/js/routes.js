import VueRouter from 'vue-router';

// import App from './views/App'
import Login from './views/Login'
import Register from './views/Register'
import Api from './views/Api'

import Database from './views/Database.vue'
import Builder from './views/database/Builder.vue'
import Crud from './views/Crud.vue'
import CrudAddEdit from './views/crud/CrudAddEdit.vue'
import Record from './views/Record.vue'
import AddEditRecord from './views/record/AddEditRecord.vue'
import Permission from './views/Permission.vue'
import Backup from './views/Backup.vue'

// Set Routes
let prefix = '/database';

const router = new VueRouter({
     mode: 'history',
     routes: [
        {
            path: prefix,
            name: 'database',
            component: Database,
            meta: {
                authorize: true,
            }
        },
        {
            path: prefix+'/table/builder/:name',
            name: 'builder',
            component: Builder,
            props: true,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'builder',
                        display: 'Builder'
                    }
                ]
            }
        },
        {
            path: prefix+'/crud',
            name: 'crud',
            component: Crud,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'crud',
                        display: 'Crud'
                    }
                ]
            }
        },
        {
            path: prefix+'/crud/:tableName/add-edit',
            name: 'crudAddEdit',
            component: CrudAddEdit,
            props: true,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'crud',
                        display: 'Crud'
                    },
                    {
                        name: 'crudAddEdit',
                        display: 'Add & Edit'
                    }
                ]
            }
        },
        {
            path: prefix+'/record/:tableName',
            name: 'record',
            component: Record,
            props: true,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'crud',
                        display: 'Crud'
                    },
                    {
                        name: 'record',
                        display: 'Record'
                    }
                ]
                // progress: {
                //     func: [
                //       {call: 'color', modifier: 'temp', argument: '#ffb000'},
                //       {call: 'fail', modifier: 'temp', argument: '#6e0000'},
                //       {call: 'location', modifier: 'temp', argument: 'top'},
                //       {call: 'transition', modifier: 'temp', argument: {speed: '1.5s', opacity: '0.6s', termination: 400}}
                //     ]
                // }
            }
        },
        {
            path: prefix+'/record/:tableName/add-edit/:id?',
            name: 'addditRecord',
            component: AddEditRecord,
            props: true,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'crud',
                        display: 'Crud'
                    },
                    {
                        name: 'record',
                        display: 'Record'
                    },
                    {
                        name: 'addditRecord',
                        display: 'Add Edit Record'
                    }
                ]
            }
        },
        {
            path: prefix+'/permission',
            name: 'permission',
            component: Permission,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'permission',
                        display: 'Permissions'
                    }
                ]
            }
        },
        {
            path: prefix+'/backup',
            name: 'backup',
            component: Backup,
            meta: {
                authorize: true,
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'backup',
                        display: 'Backups'
                    }
                ]
            }
        },
        {
            path: prefix+'/api',
            name: 'api',
            component: Api,
            meta: {
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'api',
                        display: 'Api'
                    }
                ]
            }
        },
        {
            path: prefix+'/login',
            name: 'login',
            component: Login,
            meta: {
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'login',
                        display: 'Login'
                    }
                ]
            }
        },
        {
            path: prefix+'/register',
            name: 'register',
            component: Register,
            meta: {
                breadcrumbs: [
                    {
                        name: 'database',
                        display: 'Database'
                    },
                    {
                        name: 'register',
                        display: 'Register'
                    }
                ]
            }
        },
     ],
 });

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.authorize)) {
        if (localStorage.getItem('dbm.authToken') == null) {
            router.push({
                name: "login", 
                params: {nextUrl: to.fullPath}
            })
        } else {
            next() 
        }
    } else {
        next()
    }
})

 export default router;