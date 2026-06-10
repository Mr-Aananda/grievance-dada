import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createPinia } from "pinia";
import Multiselect from "vue-multiselect";
import Complain from "./component/complain/Complain.vue";
import Grievance from "./component/grievance/Grievance.vue";


const vueApp = createApp({});
const pinia = createPinia();

// Vue-Multiselect
vueApp.component("Multiselect", Multiselect);

// Register components
vueApp.component("Complain", Complain);
vueApp.component("Grievance", Grievance);

vueApp.use(pinia);

// Mount if vueRoot exists
if (document.getElementById("vueRoot")) {
    vueApp.mount("#vueRoot");
}
