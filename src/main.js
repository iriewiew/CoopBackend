import Vue from "vue";
import "./plugins/vuetify";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import "moment/locale/th";
import axios from "axios";
import VueAxios from "vue-axios";
import VeeValidate from 'vee-validate';

import VueQrcode from '@chenfengyuan/vue-qrcode';
import VueFusionCharts from 'vue-fusioncharts';
import FusionCharts from 'fusioncharts/core';
import FusionTheme from 'fusioncharts/themes/es/fusioncharts.theme.fusion'
import Column2D from 'fusioncharts/viz/column2d';
import Doughnut2D from 'fusioncharts/viz/doughnut2d';

Vue.component(VueQrcode.name, VueQrcode);
const config = {
  aria: true,
  classNames: {},
  classes: false,
  delay: 0,
  dictionary: null,
  errorBagName: 'errors', // change if property conflicts
  events: 'input|blur',
  fieldsBagName: 'fields',
  i18n: null, // the vue-i18n plugin instance
  i18nRootKey: "validations", // the nested key under which the validation messages will be located
  inject: true,
  locale: "th",
  validity: false,
  useConstraintAttrs: true
};
Vue.use(VueFusionCharts, FusionCharts, FusionTheme, Column2D, Doughnut2D);
Vue.use(VeeValidate, config);
Vue.use(VueAxios, axios);

// Vue.use(Buefy);

Vue.config.productionTip = false;
Vue.use(require('vue-moment'));

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
