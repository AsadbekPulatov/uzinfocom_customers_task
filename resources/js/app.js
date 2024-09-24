import './bootstrap';

import {createApp} from "vue/dist/vue.esm-bundler";
import DownloadButton from "./components/DownloadButton.vue";

const app = createApp({
    components: {
        DownloadButton
    }
})

app.mount('#app')
