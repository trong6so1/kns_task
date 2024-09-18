<template>
  <Menubar
    :model="items"
    class="w-100 text-white"
    :style="`background-color: ${background.headerColor}`"
    focusOnHover="false"
  >
    <template #start>
      <router-link :to="{ name: 'Dashboard' }">
        <span class="logo fs-5 text-white"
          ><span class="fw-bold">S-Cart </span>Admin</span
        >
      </router-link>
    </template>
    <template #item="{ item, props, hasSubmenu, root }">
      <a
        class="flex items-center mx-4"
        :class="{ 'bg-white px-2 py-1': !root }"
        v-bind="props.action"
      >
        <img :src="item.image" alt="" v-if="item.image" />
        <span :class="item.icon" />
        <span class="ms-2" :class="{ 'text-white': root, 'text-dark': !root }"
        >{{
          item.label
        }}</span>
        <span :class="{ 'text-white': root, 'text-dark': !root }" @click="changeTheme(item)" v-if="item.headerColor" >{{
          item.name
        }}</span>
        <i
          v-if="hasSubmenu && !item.image"
          :class="[
            'pi pi-angle-down',
            { 'pi-angle-down ml-2': root, 'pi-angle-right ml-auto': !root },
          ]"
        ></i>
      </a>
    </template>
    <template #end>
      <div class="flex items-center gap-2"></div>
    </template>
  </Menubar>
</template>

<script setup>
import { onMounted, ref } from "vue";
import Menubar from "primevue/menubar";
import { field } from "./field.js";
import stores from '@/stores/index.js'

const items = ref([]);
const background = ref({});
const changeTheme = (theme) => {
  stores.dispatch('setTheme', theme);
  background.value = stores.state.theme.theme;
};
onMounted(() => {
  items.value = field;
  background.value = stores.state.theme.theme;
});
</script>

<style>
.p-menubar{
  padding: 0;
}
</style>

<style scoped>
::v-deep .p-menubar-start {
  width: 15%;
  justify-content: center;
  background-color: #c7626c !important;
  padding: 15px;
}
.logo {
  font-size: 1.25rem;
  line-height: 1.5;
}
</style>
