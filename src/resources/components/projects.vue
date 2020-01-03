<template>
  <div class="todobar-projects-container">
    <select ref="todobar-projects-select" v-model="active_project" @change="inputChanged()">
      <option value="-1" selected>Select a Project</option>
      <option v-for="(project, index) in projects_list" :key="index" :value="index">{{ project }}</option>
    </select>
    <button type="button" class="todobar-button-primary" @click="add()">Add</button>
    <button type="button" class="todobar-button-secondary" @click="edit()">Ren</button>
    <button type="button" class="todobar-button-danger" @click="remove()">Del</button>
  </div>
</template>

<script>
import { EventBus } from "./../eventbus.js";

export default {
  data() {
    return {
      active_project: null,
      projects_list: []
    };
  },
  mounted() {
    this.active_project = EventBus.remember();
    this.get(this.active_project);
  },
  methods: {
    inputChanged() {
      EventBus.memorize(this.active_project);
      EventBus.$emit("todobar:load-tasks", this.active_project);
    },
    get: function(initial) {
      EventBus.get("projects", result => {
        this.projects_list = [];
        result.data.forEach((element, key) => {
          this.projects_list.push(element);
        });
        this.active_project = initial == undefined || initial == null ? -1 : initial;
        if (this.active_project == null) {
          this.active_project = -1;
        }
        this.inputChanged()
      });
    },
    add: function() {
      let project_name = prompt("What will be the new project's name?");
      if (project_name != null) {
        EventBus.post(
          "projects",
          {
            name: project_name
          },
          result => {
            if (result.status == "success") {
              this.get(parseInt(result.id, 10));
            } else if (result.status == "error") {
              alert(result.error);
            }
          }
        );
      }
    },
    edit: function() {
      let selected_item = this.$refs["todobar-projects-select"].selectedOptions[0],
        project_name = selected_item.text,
        project_id = selected_item.value;
      if (project_id && project_id >= 0) {
        let new_project_name = prompt("What will be the new project name?", project_name);
        if (new_project_name !== null) {
          EventBus.patch(
            "projects/" + project_id,
            {
              name: new_project_name
            },
            result => {
              if (result.status == "success") {
                this.get(parseInt(project_id, 10));
              } else if (result.status == "error") {
                alert(result.error);
              }
            }
          );
        }
      } else {
        alert("You need to select a project first to change it's name!");
      }
    },
    remove: function() {
      let selected_item = this.$refs["todobar-projects-select"].selectedOptions[0],
        project_id = selected_item.value;
      if (project_id && project_id >= 0) {
        if (
          confirm(
            "Are you sure to delete this project? This will also remove all associated tasks. And can't be undone. Are you still sure?"
          )
        ) {
          EventBus.delete("projects/" + project_id, result => {
            if (result.status == "success") {
              this.get();
            } else if (result.status == "error") {
              alert(result.error);
            }
          });
        }
      } else {
        alert("You need to select a project first to delete it!");
      }
    }
  }
};
</script>

<style lang="scss">
.todobar-projects-container {
  width: 100%;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  background: #fcfcfd;
  select {
    padding: 5px;
    margin-left: 4px;
    flex: 1;
    height: 28px;
    font-size: 10px;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    margin: 0;
  }
  button {
    margin-left: 2px;
    height: 28px;
    border: 1px solid #ddd;
    border-radius: 0.25rem;
    color: white;
    font-size: 11px;
    width: 40px;

    &.todobar-button-secondary {
      background: #6C757D;
      border-color: lighten(#6c757d, 5%);
    }

    &.todobar-button-danger {
      background: #E3342F;
      border-color: lighten(#E3342F, 5%);
    }

    &.todobar-button-primary {
      background: #3490DC;
      border-color: lighten(#3490DC, 5%);
    }
  }
}
</style>
