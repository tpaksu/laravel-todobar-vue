import Vue from 'vue'
import LaravelTodobar from './resources/App.vue'

Vue.config.productionTip = false
Vue.config.devtools = false

new Vue({
  render: h => h(LaravelTodobar),
}).$mount('#laravel-todobar')
