import '@/assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config'
import Nora from '@primevue/themes/nora';
const app = createApp(App)
app.use(createPinia())
app.use(router)
app.use(PrimeVue,{ theme: {
    preset: Nora
}})
app.mount('#app')
