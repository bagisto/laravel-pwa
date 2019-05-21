import Vue     from 'vue';
import VueI18n from 'vue-i18n';
import en      from '../lang/en.json'
Vue.use(VueI18n);

const messages = {
    'en': en
};

export default new VueI18n({
    locale: window.config.currentLocale.code, // set locale
    fallbackLocale: 'en', // set fallback locale
    messages, // set locale messages
});