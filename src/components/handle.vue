<template>
  <div class="laravel-todobar-handle" @click="toggleVisibility()">
    <svg
      height="18px"
      version="1.1"
      viewBox="0 0 18 18"
      width="18px"
      xmlns="http://www.w3.org/2000/svg"
      xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
      xmlns:xlink="http://www.w3.org/1999/xlink"
    >
      <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">
        <g fill="#fff" id="Core" transform="translate(-465.000000, -45.000000)">
          <g id="check-box" transform="translate(465.000000, 45.000000)">
            <path
              d="M16,0 L2,0 C0.9,0 0,0.9 0,2 L0,16 C0,17.1 0.9,18 2,18 L16,18 C17.1,18 18,17.1 18,16 L18,2 C18,0.9 17.1,0 16,0 L16,0 Z M7,14 L2,9 L3.4,7.6 L7,11.2 L14.6,3.6 L16,5 L7,14 L7,14 Z"
              id="Shape"
            />
          </g>
        </g>
      </g>
    </svg>
  </div>
</template>

<script>
export default {
  methods: {
    toggleVisibility() {
      document.querySelector("html").classList.toggle("todobar-active");
    }
  }
};
</script>

<style lang="scss">
.laravel-todobar-handle {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  position: absolute;
  top: 57px;
  background: #ff2d20;
  font-weight: bold;
  left: -37px;
  padding: 9px;
  border-radius: 5px 0 0 5px;
  box-shadow: -2px 0 2px -1px rgba(0, 0, 0, 0.4);
  z-index: -1;
}
</style>
