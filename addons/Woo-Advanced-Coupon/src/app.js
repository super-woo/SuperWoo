import Vue from "vue";
window.axios = require("axios");
window.Qs = require('qs');
import Toasted from 'vue-toasted';

Vue.use(Toasted)

Vue.component("supertype", require("./components/type.vue").default);
Vue.component("superfilter", require("./components/filter.vue").default);
Vue.component("superdiscount", require("./components/discount.vue").default);
Vue.component("superrules", require("./components/rules.vue").default);
Vue.component("supertabs", require("./components/tabs.vue").default);

new Vue({
  el: "#post",
  data: {
    superwoo_coupon_form: {
      type: "product"
    }
  }
});
