import Vue from 'vue'
import Vuetify from 'vuetify'

Vue.use(Vuetify)

const opts = {
    theme: {
        dark: false,
        themes: {
            light: {
                primary: '#FB397D'
            }
        }
    }
}

export default new Vuetify(opts)
