require('./bootstrap')

import Vue from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue'
import {InertiaProgress} from '@inertiajs/progress'

import vuetify from "@/Plugins/vuetify";
import store from '@/Plugins/vuex'
import Vuelidate from 'vuelidate';

import AdminLayout from "@/Layouts/AdminLayout";
import UsersLayout from "@/Layouts/UsersLayout";
import Guest from "@/Layouts/Guest";

Vue
    .mixin({
        methods: {
            route,
            visit(url) {
                this.$inertia.visit(url, {
                    method: 'get'
                })
            },
        }
    })
    .use(Vuelidate)

createInertiaApp({
    resolve: name => import(`./Pages/${name}`).then(({default: page}) => {
         if (!page.layout) {
             if (name.includes('Admin')) {
                 page.layout = AdminLayout
             } else if (name.includes('Users')) {
                 page.layout = UsersLayout
             } else {
                 page.layout = Guest
             }
         }

         return page
    }),
    setup({el, App, props, plugin}) {
        Vue.use(plugin)

        new Vue({
            store,
            vuetify,
            render: h => h(App, props),
        }).$mount(el)
    },
})

InertiaProgress.init()
