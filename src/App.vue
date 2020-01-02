<template>
  <div class="laravel-todobar">
    <TodoBarHandle>
    </TodoBarHandle>
    <TodoBarPanel>
      <TodoBarProjects/>
      <div class="laravel-todobar-content">
        <h3>Tasks</h3>
        <TodoBarForm/>
        <TodoBarTasks/>
      </div>
    </TodoBarPanel>
  </div>
</template>

<script>
import TodoBarHandle from './components/handle.vue'
import TodoBarProjects from './components/projects.vue'
import TodoBarTasks from './components/tasks.vue'
import TodoBarForm from './components/form.vue'
import TodoBarPanel from './components/panel.vue'

export default {
  name: 'laravel-todobar',
  components: {
    TodoBarHandle, TodoBarProjects, TodoBarTasks, TodoBarForm, TodoBarPanel
  }
}
</script>

<style lang="scss">
  @import "assets/todobar.scss";
</style>
