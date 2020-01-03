<template>
  <transition name="todobar-modal">
    <div class="todobar-modal-mask" v-if="showModal">
      <div class="todobar-modal-wrapper">
        <div class="todobar-modal-container">
          <div class="todobar-modal-header">
            <h5 name="todobar-header">Edit Task</h5>
            <button class="todobar-close" data-dismiss="todobar-modal" aria-label="Close" @click="showModal = false">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="todobar-modal-body">
            <input type="hidden" v-model="project_id" />
            <input type="hidden" v-model="task_id" />
            <textarea style="min-width: 100%" rows="6" v-model="task"></textarea>
          </div>
          <div class="todobar-modal-footer">
            <button type="button" class="todobar-button-primary" @click="saveEdits()">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { EventBus } from "./../eventbus.js";
export default {
  components: {
    EventBus
  },
  methods: {
    saveEdits() {
      EventBus.$emit("todobar:edit-task", this.project_id, this.task_id, this.task);
    }
  },
  data() {
    return {
      project_id: -1,
      task_id: -1,
      task: "",
      showModal: false
    };
  },
  mounted() {
    EventBus.$on("todobar:show-edit-modal", (project_id, task_id, task) => {
      this.project_id = project_id;
      this.task_id = task_id;
      this.task = task;
      this.showModal = true;
    }).$on("todobar:hide-edit-modal", () => {
      this.showModal = false;
    });
  },
  beforeDestroy() {
    EventBus.$off("todobar:show-edit-modal").$off("todobar:hide-edit-modal");
  }
};
</script>

<style lang="scss" scoped>
.todobar-modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.todobar-modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.todobar-modal-container {
  width: 300px;
  margin: 0px auto;
  padding: 0;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
  font-family: Helvetica, Arial, sans-serif;
}

.todobar-modal-header {
  padding: 15px;
  background: #f7f7f7;
  border-bottom: 1px solid #dee2e6;
  position: relative;
  h5 {
      margin-bottom: 0;
  }
  .todobar-close {
    background: transparent;
    border: none;
    font-size: 1rem;
    padding: 0;
    margin: 0;
    line-height: 1rem;
    position: absolute;
    right:15px;
    top: 15px;
  }
}
.todobar-modal-footer {
  padding: 15px;
  background: #f7f7f7;
  text-align: right;
  button {
    background: #007bff;
    display: inline-block;
    font-weight: 400;
    color: #fff;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    border: 1px solid lighten(#007bff, 5%);
    padding: 0.375rem 0.75rem;
    font-size: 0.8rem;
    line-height: 1.8;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out,
      box-shadow 0.15s ease-in-out;
  }
}

.todobar-modal-body {
  padding: 15px;
  textarea {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    padding-right: 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  }
}

.todobar-modal-default-button {
  float: right;
}

.todobar-modal-enter {
  opacity: 0;
}

.todobar-modal-leave-active {
  opacity: 0;
}

.todobar-modal-enter .modal-container,
.todobar-modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>
