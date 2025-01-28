import './bootstrap';
import { createApp } from 'vue'
import Exchanger from './components/Exchanger.vue'

const app = createApp()
app.component('exchanger', Exchanger)
//
app.mount('#app')
