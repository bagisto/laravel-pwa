import Vue     from 'vue';
import VueI18n from 'vue-i18n';
import en      from '../lang/en.json'
import ar      from '../lang/ar.json'
Vue.use(VueI18n);

const messages = {
    'ar': ar,
};

export default new VueI18n({
    locale: window.config.currentLocale.code, // set locale
    fallbackLocale: 'ar', // set fallback locale
    messages, // set locale messages
});