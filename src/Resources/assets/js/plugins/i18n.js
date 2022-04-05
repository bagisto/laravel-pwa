import Vue     from 'vue';
import VueI18n from 'vue-i18n';
import en      from '../lang/en.json';
import ar      from '../lang/ar.json';
import de      from '../lang/de.json';
import fa      from '../lang/fa.json';
import fr      from '../lang/fr.json';
import it      from '../lang/it.json';
import ja      from '../lang/ja.json';
import nl      from '../lang/nl.json';
import pl      from '../lang/pl.json';
import tr      from '../lang/tr.json';
import pt_BR   from '../lang/pt_BR.json';

Vue.use(VueI18n);

const messages = {
    'en'    : en,
    'fr'    : fr,
    'ar'    : ar,
    'de'    : de,
    'fa'    : fa,
    'it'    : it,
    'ja'    : ja,
    'nl'    : nl,
    'pl'    : pl,
    'tr'    : tr,
    'pt_BR' : pt_BR,
};

export default new VueI18n({
    locale          : window.config.currentLocale.code, // set locale
    fallbackLocale  : 'en',                             // set fallback locale
    messages,                                           // set locale messages
});