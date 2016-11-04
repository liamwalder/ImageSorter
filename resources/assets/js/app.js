require('./bootstrap');

var Upload = Vue.component('image-upload', require('./components/Upload.vue'));
var Order = Vue.component('image-order', require('./components/Order.vue'));
var Download = Vue.component('image-order', require('./components/Download.vue'));

/**
 * Routes
 * @type {*[]}
 */
const routes =  [
    { path: '/', component: Upload },
    { path: '/order', name: 'order', component: Order },
    { path: '/download', name: 'download', component: Download }
];


/**
 * Default state
 * @type {*|Store}
 */
const store = new VueX.Store({
    state: {
        uniqueId: 0,
        zipUrl: null
    },
});

/**
 * Setup router and beforeEach rule
 */
const router = new VueRouter({ routes });
router.beforeEach((to, from, next) => {
    if (to.path != '/') {
        if (store.state.uniqueId == 0) {
            next('/');
        }
    }
    next();
})

const app = new Vue({ router, store }).$mount('#container');