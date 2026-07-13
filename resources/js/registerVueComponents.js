import { createApp } from "vue/dist/vue.esm-bundler.js";
import { createPinia } from "pinia";
import Multiselect from "vue-multiselect";
import Grievance from "./component/grievance/Grievance.vue";


const vueApp = createApp({
    data() {
        return {
            activeTab: 'submit',
            totalRecords: 0
        };
    }
});
const pinia = createPinia();

// Global translation function for Vue components
vueApp.config.globalProperties.$t = function (key) {
    if (window.translations && window.translations[key] !== undefined) {
        return window.translations[key];
    }
    return key;
};

// Vue-Multiselect
vueApp.component("Multiselect", Multiselect);

// Register components
vueApp.component("Grievance", Grievance);

vueApp.use(pinia);

// Mount if vueRoot exists
if (document.getElementById("vueRoot")) {
    vueApp.mount("#vueRoot");
}
