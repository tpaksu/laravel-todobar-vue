<template>
  <div class="laravel-todobar-form">
    <textarea placeholder="Task Description" name="description"></textarea>
    <button @click="saveTask">
      Push the task!
    </button>
  </div>
</template>

<script>
export default {
  methods: {
    saveTask(){

    }
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
    border-radius: .25rem 0 0 .25rem;
    padding: 10px;
    height: 40px;
    border: 1px solid #ddd;
  }
  button {
    flex: 0 0 70px;
    border-radius: 0 .25rem .25rem 0;
    background: #007BFF;
    border: 1px solid #007BFF;
    color: white;
  }
}
</style>
