<template>
  <Header />
  <div class="row">
    <div class="menu">
      <Menu />
    </div>
  </div>
</template>
<script setup>
import Header from "./header/index.vue";
import Menu from "./menu/index.vue"
</script>

<style scoped>
.menu {
  width: 15%;
}
</style>