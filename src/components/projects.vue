<template>
  <div class="todobar-projects-container">
    <select class="todobar-projects-select" @change="inputChanged()">
      <option value="-1">Select a Project</option>
    </select>
    <button type="button" class="btn btn-sm btn-primary" onclick="todobar.projects.add()">Add</button>
    <button type="button" class="btn btn-sm btn-secondary" onclick="todobar.projects.edit()">Ren</button>
    <button type="button" class="btn btn-sm btn-danger" onclick="todobar.projects.delete()">Del</button>
  </div>
</template>

<script>
export default {};
</script>

<style lang="scss"></style>
