<template>
  <ul class="laravel-todobar-list">
    <li
      v-for="(task, idx) in this.$data.tasks"
      :key="idx"
      :class="'laravel-todobar-task ' + (idx % 2 ? 'custom-checkbox-even' : 'custom-checkbox-odd')"
    >
      <div style="display: flex;">
        <input
          type="checkbox"
          :ref="'todobar-task-' + idx"
          :checked="task.completed"
          @click="setTaskStatus(active_project, idx, !task.completed)"
        />
        <label>
          <div class="checkbox-dummy" @click="check('todobar-task-' + idx)">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              width="11pt"
              height="11pt"
              viewBox="0 0 16 13"
              version="1.1"
            >
              <g id="surface1">
                <path
                  style=" stroke:none;fill-rule:nonzero;fill:white;fill-opacity:1;"
                  d="M 5.96875 12.878906 L 0 6.90625 L 2.847656 4.058594 L 5.96875 7.179688 L 13.152344 0 L 16 2.847656 Z M 5.96875 12.878906 "
                />
              </g>
            </svg>
          </div>
          <div class="label-content">
            {{ task.content }}&nbsp;
            <a href="javascript:void(0);" class="edit-task" @click="editTaskForm(active_project, idx, task.content)">Edit</a>&nbsp;
            <a href="javascript:void(0);" class="delete-task" @click="removeTask(active_project, idx)">Delete</a>
          </div>
        </label>
      </div>
    </li>
  </ul>
</template>

<script>
import { EventBus } from "./../eventbus.js";

export default {
  data() {
    return {
      active_project: null,
      tasks: []
    };
  },
  methods: {
    check(ref) {
      var checkbox = this.$refs[ref][0];
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event("click"));
    },
    getTasks: function(project_id) {
      var that = this;
      EventBus.get("/projects/" + project_id + "/tasks", function(result) {
        if (result.status == "success") {
          that.tasks = result.tasks;
          that.active_project = project_id;
        } else if (result.status == "error") {
          alert(result.error);
        }
      });
    },
    addTask: function(task) {
      let project_id = this.active_project;
      var that = this;
      EventBus.post(
        "/projects/" + project_id + "/tasks",
        {
          content: task
        },
        function(result) {
          if (result.status == "success") {
            EventBus.$emit("todobar:load-tasks", project_id);
            EventBus.$emit("todobar:clear-task-input");
          } else if (result.status == "error") {
            alert(result.error);
          }
        }
      );
    },
    editTaskForm: function(project_id, task_id, task) {
      EventBus.$emit("todobar:show-edit-modal", project_id, task_id, task);
    },
    editTask: function(project_id, task_id, content) {
      var that = this;
      EventBus.patch(
        "/projects/" + project_id + "/tasks/" + task_id,
        {
          content: content
        },
        function(result) {
          if (result.status == "success") {
            that.getTasks(project_id);
            EventBus.$emit("todobar:hide-edit-modal");
          } else if (result.status == "error") {
            alert(result.error);
          }
        }
      );
    },
    removeTask: function(project_id, task_id) {
      var that = this;
      EventBus.delete("/projects/" + project_id + "/tasks/" + task_id, function(result) {
        if (result.status == "success") {
          that.getTasks(project_id);
        } else if (result.status == "error") {
          alert(result.error);
        }
      });
    },
    setTaskStatus: function(project_id, task_id, completed) {
      var that = this;
      EventBus.patch("/projects/" + project_id + "/tasks/" + task_id, { status: completed }, function(result) {
        if (result.status == "success") {
          that.getTasks(project_id);
        } else if (result.status == "error") {
          alert(result.error);
        }
      });
    }
  },
  mounted() {
    var that = this;
    EventBus.$on("todobar:load-tasks", project_id => {
      that.getTasks(project_id);
    })
      .$on("todobar:save-task", task => {
        that.addTask(task);
      })
      .$on("todobar:edit-task", (project_id, task_id, content) => {
        that.editTask(project_id, task_id, content);
      });
  },
  beforeDestroy() {
    EventBus.$off("todobar:load-tasks")
      .$off("todobar:edit-task")
      .$off("todobar:save-task");
  }
};
</script>

<style lang="scss">
ul.laravel-todobar-list {
  list-style-type: none;
  padding: 0;
  line-height: 1.5;
  overflow-y: scroll;
  flex: 1;
  margin-bottom: 0;

  li {
    padding: 15px;
    border-bottom: 1px solid #dde;

    label {
      margin-bottom: 0;
    }

    input:checked + label .checkbox-dummy {
      background: green;
    }

    .edit-task {
        color: #3490DC;
    }

    .delete-task {
        color: #E3342F;
    }

    label {
      display: block;
      padding-left: 30px;
      margin-top: -20px;
    }

    input[type="checkbox"] {
      display: none;
    }

    .checkbox-dummy {
      background: white;
      color: white;
      border: 1px solid #aaa;
      width: 20px;
      height: 20px;
      transform: translateY(100%) translateX(-30px);
      border-radius: 5px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    input:checked + label .label-content {
      color: slategray;
      text-decoration: line-through;
      text-decoration-color: slategray;
    }

    &.custom-checkbox-odd {
      background: #f9f9f9;
    }
  }
}
</style>
