<template>
  <div class="laravel-todobar-form">
    <textarea placeholder="Task Description" ref="todobar-task-input" v-model="new_task"></textarea>
    <button @click="saveTask()">
      Push the task!
    </button>
  </div>
</template>

<script>
import { EventBus } from "./../eventbus.js";
export default {
  data() {
    return {
      new_task: ""
    };
  },
  methods: {
    saveTask() {
      EventBus.$emit("todobar:save-task", this.new_task);
    }
  },
  mounted() {
    EventBus.$on("todobar:clear-task-input", () => {
      this.new_task = "";
      this.refs["todobar-task-input"].focus();
    });
  },
  beforeDestroy() {
    EventBus.$off("todobar:clear-task-input");
  }
};
</script>

<style lang="scss">
.laravel-todobar-form {
  flex: 0;
  padding: 15px;
  background: #fcfcfd;
  border-bottom: 1px solid #ddd;
  display: flex;
  textarea {
    flex: 1;
    border-radius: 0.25rem 0 0 0.25rem;
    padding: 10px;
    border: 1px solid #ddd;
  }
  button {
    flex: 0 0 50px;
    border-radius: 0 0.25rem 0.25rem 0;
    background: #3490DC;
    border: 1px solid #3490DC;
    color: white;
  }
}
</style>
