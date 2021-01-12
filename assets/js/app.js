import Vue from 'vue';
import App from './components/App';
import { userInfo } from 'os';

new Vue({
    el: '#app',
    render: h => h(App),
})
