import Vue from 'vue';
export const EventBus = new Vue({
    methods: {
        remember() {
            return window.localStorage.getItem("laravel-todobar-last-project");
        },
        memorize(value) {
            window.localStorage.setItem("laravel-todobar-last-project", parseInt(value, 10));
        },
        get: function (endpoint, callback) {
            this.fetch("GET", endpoint, undefined, callback);
        },
        delete: function (endpoint, callback) {
            this.fetch("DELETE", endpoint, undefined, callback);
        },
        post: function (endpoint, data, callback) {
            this.fetch("POST", endpoint, data, callback);
        },
        patch: function (endpoint, data, callback) {
            this.fetch("PATCH", endpoint, data, callback);
        },
        fetch: function (method, endpoint, data, callback) {
            EventBus.$emit("todobar:show-loader");
            fetch("/laravel-todobar/" + endpoint.replace(/^\/+/g, ''), {
                    method: method,
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        "Authorization": "Bearer " + window.todoBarToken
                    },
                    body: (data !== undefined) ? JSON.stringify(data) : null
                }).then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    alert(`Request failed: ${error}`)
                }).then((result) => {
                    if (result) {
                        callback(result);
                    }
                }).finally(() => {
                    EventBus.$emit("todobar:hide-loader");
                });
        }
    }
});
