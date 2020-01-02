<template>
  <div class="todobar-projects-container">
    <select class="todobar-projects-select" v-model="active_project" @change="inputChanged()">
      <option value="-1" selected>Select a Project</option>
      <option v-for="(project, index) in this.$data.projects_list" :key="index" :value="index">{{ project }}</option>
    </select>
    <button type="button" class="btn-primary" onclick="todobar.projects.add()">Add</button>
    <button type="button" class="btn-secondary" onclick="todobar.projects.edit()">Ren</button>
    <button type="button" class="btn-danger" onclick="todobar.projects.delete()">Del</button>
  </div>
</template>

<script>
import { EventBus } from "./../eventbus.js";

export default {
  data() {
    return {
      active_project: null,
      project_list: []
    };
  },
  mounted(){
    this.active_project = EventBus.remember();
    if(this.active_project === null) this.active_project = -1;
  },
  methods: {
    inputChanged(input) {
      EventBus.memorize(active_project);
    }
  }
};
</script>

<style lang="scss">
.todobar-projects-container {
  width: calc(100% - 30px);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  background: #fcfcfd;
  select {
    padding: 5px;
    margin-left: 4px;
    flex: 1;
    height: 24px;
    font-size: 10px;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    margin: 0;
  }
  button {
    margin-left: 2px;
    height: 24px;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    color: white;
    font-size: 10px;
    font-weight: 600;
    width: 40px;

    &.btn-secondary {
      background: #6c757d;
      border-color: lighten(#6c757d, 5%);
    }

    &.btn-danger {
      background: #dc3545;
      border-color: lighten(#dc3545, 5%);
    }

    &.btn-primary {
      background: #007bff;
      border-color: lighten(#007bff, 5%);
    }
  }
}
</style>
