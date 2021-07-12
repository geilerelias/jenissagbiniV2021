import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify)

const opts = {
    icons: {
        iconfont: 'mdi', // default - only for display purposes
    },
    theme: {
        themes: {
            light: {
                primary: '#272727',
                secondary: '#9e9e9e',
                accent: '#212121',
                error: '#f44336',
                warning: '#ff9800',
                info: '#00bcd4',
                success: '#4caf50',
            },
            dark: {
                primary: '#272727',
                secondary: '#9e9e9e',
                accent: '#212121',
                error: '#f44336',
                warning: '#ff9800',
                info: '#00bcd4',
                success: '#4caf50',
            },
        },
    },
}

export default new Vuetify(opts)
