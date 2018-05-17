
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router'


Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


//Vue.component('formhub', require('./components/Form.vue'));
//Vue.component('selectedit', require('./components/SelectEdlitList.vue'));
//Vue.component('uform', require('./components/UserForm.vue'));
const Home = { template: '<div>This is Home</div>' };
const routes = [
    {name:'home',path:'/',component:Home},
    {name:'step0',path:'/form/:geturl/:posturl/:heading/:step/:table', component: require('./components/Form.vue'),props:true},
    {name:'step1',path:'/form/:geturl/:posturl/:heading/:step/:table/:language', component: require('./components/Form.vue'),props:true},
    {name:'step2',path:'/form/:geturl/:posturl/:heading/:step/:table/:language', component: require('./components/Form.vue'),props:true},
    {name:'selectoption',path:'/selectedit/:language/:formid/:table', component: require('./components/SelectEdlitList.vue'),props:true},
    {name:'newuserform',path:'/uform/:geturl', component : require('./components/UserForm.vue'),props:true},
    {name:'edituserform',path:'/uform/:geturl/:id', component : require('./components/UserForm.vue'),props:true}
];



const router = new VueRouter({
  routes, 
})

const app = new Vue({ router }).$mount('#app')


