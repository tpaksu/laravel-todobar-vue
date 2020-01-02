<template>
<ul class="">
    <li v-for="(task, idx) in this.$data.tasks" :key="idx" :class="'laravel-todobar-task ' + (idx % 2 ? 'custom-checkbox-even' : 'custom-checkbox-odd')">
        <div class="form-group">
            <input type="checkbox" :id="'todobar-task-' + idx" :checked="task.completed" @click="this.setStatus(this.$props.active_project, idx, !task.completed);">
        <label>
            <div class="checkbox-dummy" @click="this.check('todobar-task-'+idx);">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="11pt" height="11pt" viewBox="0 0 16 13" version="1.1">
                    <g id="surface1">
                        <path style=" stroke:none;fill-rule:nonzero;fill:white;fill-opacity:1;" d="M 5.96875 12.878906 L 0 6.90625 L 2.847656 4.058594 L 5.96875 7.179688 L 13.152344 0 L 16 2.847656 Z M 5.96875 12.878906 "/>
                    </g>
                </svg>
            </div>
            <div class="label-content">
                {{task.content}}&nbsp;<a href="javascript:void(0);" class="text-primary" @click="this.edit_form(this.$props.active_project, idx, task.content);">Edit</a>
                <a href="javascript:void(0);" class="text-danger" @click="this.delete(this.$props.active_project, $idx);">Delete</a>
            </div>
        </label>
        </div>
    </li>
</ul>

</template>

<script>
import { EventBus } from "./../eventbus.js";

export default {
    props: {
        active_project: null
    },
    methods: {
        check(ref) {
            var checkbox = this.$refs[ref];
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('click'));
        },
        get: function (project_id) {
            EventBus.get("/projects/" + project_id + "/tasks", function (result) {
                if (result.status == "success") {
                    document.querySelector(".laravel-todobar-list").innerHTML = result.html;
                } else if (result.status == "error") {
                    alert(result.error);
                }
            });
        },
        add: function (task) {
            let project_id = this.active_project;
            EventBus.post("/projects/" + project_id + "/tasks", {
                content: task
            }, function (result) {
                if (result.status == "success") {
                    EventBus.$emit("todobar:load-tasks", project_id);
                    EventBus.$emit("todobar:clear-task-input");
                    todobar.tasks.get(project_id);
                    task_input.value = "";
                    task_input.focus();
                } else if (result.status == "error") {
                    alert(result.error);
                }
            });
        },
        edit_form: function(project_id, task_id, task){
            document.querySelector("#laravel-todobar_project_id").value = project_id;
            document.querySelector("#laravel-todobar_task_id").value = task_id;
            document.querySelector("#laravel-todobar_content").value = task;
            $("#laravel-todobar-edit-modal").modal("show");
        },
        edit: function () {
            let project_id = document.querySelector("#laravel-todobar_project_id").value,
            task_id = document.querySelector("#laravel-todobar_task_id").value,
            content = document.querySelector("#laravel-todobar_content").value;

            todobar.fetcher.patch("/projects/" + project_id + "/tasks/" + task_id, {
                content: content
            }, function (result) {
                if (result.status == "success") {
                    todobar.tasks.get(project_id);
                    $("#laravel-todobar-edit-modal").modal("hide");
                } else if (result.status == "error") {
                    alert(result.error);
                }
            });
        },
        delete: function (project_id, task_id) {
            todobar.fetcher.delete("/projects/" + project_id + "/tasks/" + task_id, function (result) {
                if (result.status == "success") {
                    todobar.tasks.get(project_id);
                } else if (result.status == "error") {
                    alert(result.error);
                }
            });
        },
        setStatus: function (project_id, task_id, completed) {
            todobar.fetcher.patch("/projects/" + project_id + "/tasks/" + task_id, {status: completed}, function (result) {
                if (result.status == "success") {
                    todobar.tasks.get(project_id);
                } else if (result.status == "error") {
                    alert(result.error);
                }
            });
        }
    }
};
</script>

<style lang="scss"></style>
