require('./bootstrap')

import Vue from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue'
import {InertiaProgress} from '@inertiajs/progress'

import vuetify from "@/Plugins/vuetify";
import store from '@/Plugins/vuex'
import Vuelidate from 'vuelidate';

import DashboardLayout from "@/Layouts/DashboardLayout";
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
            page.layout = name.includes('Dashboard') ? DashboardLayout : Guest
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
