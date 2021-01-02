import Vue     from 'vue';
import VueI18n from 'vue-i18n';
import en      from '../lang/en.json'
import pt_BR      from '../lang/pt_BR.json'
Vue.use(VueI18n);

const messages = {
    'en': en,
    'pt_BR': pt_BR
};

export default new VueI18n({
    locale: window.config.currentLocale.code, // set locale
    fallbackLocale: 'en', // set fallback locale
    messages, // set locale messages
});