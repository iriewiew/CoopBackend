import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import 'vuetify/src/stylus/app.styl'
import th from 'vuetify/es5/locale/th'
Vue.use(Vuetify, {
  iconfont: 'md',
  lang: {
    locales: { th },
    current: 'th'
  }
})
