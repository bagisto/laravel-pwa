import Vue     from 'vue';
import VueI18n from 'vue-i18n';
import en      from '../lang/en.json'
<<<<<<< HEAD
import ar      from '../lang/ar.json'
Vue.use(VueI18n);

const messages = {
    'ar': ar,
=======
Vue.use(VueI18n);

const messages = {
    'en': en
>>>>>>> 357d53b426de9235e5e2207552184692782d0dcb
};

export default new VueI18n({
    locale: window.config.currentLocale.code, // set locale
<<<<<<< HEAD
    fallbackLocale: 'ar', // set fallback locale
=======
    fallbackLocale: 'en', // set fallback locale
>>>>>>> 357d53b426de9235e5e2207552184692782d0dcb
    messages, // set locale messages
});