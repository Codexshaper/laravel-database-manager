/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
require('nestable2');
window.Swal = require('sweetalert2');
window.toastr = require('toastr');

window.Vue = require('vue');

import _ from 'lodash'

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

import 'v-markdown-editor/dist/index.css';

import Editor from 'v-markdown-editor'
Vue.use(Editor);

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '0.2s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
})
// Root Component
Vue.component('database-app', require('./views/App.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));
// Mixins
import mixin from './mixin.js'
Vue.mixin(mixin)
// Routes
import router from './routes.js'

const app = new Vue({
    el: '#app',
    router,
});