<template>
  <Menubar
    :model="items"
    class="w-100 text-white"
    :style="'background-color : ' + background"
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
      <Select
        v-model="background"
        :options="hasSubmenu"
        optionLabel="name"
        placeholder="Theme"
        class="w-full md:w-56"
        v-if="item.label == 'Theme'"
        @change="changeTheme(item.value)"
      />

      <a
        class="flex items-center mx-4"
        :class="{ 'bg-white px-2 py-1': !root }"
        v-bind="props.action"
        v-else
      >
        <img :src="item.image" alt="" v-if="item.image" />
        <span :class="item.icon" />
        <span class="ml-2" :class="{ 'text-white': root, 'text-dark': !root }">{{
          item.label
        }}</span>
        <i
          v-if="hasSubmenu && !item.image"
          :clsas="[
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
import { field, theme } from "./field.js";
import Select from "primevue/select";
const items = ref([]);
const background = ref("");
const changeTheme = (theme) => {
  console.log(theme);
};
onMounted(() => {
  console.log(field);
  items.value = field;
  if (!background.value) background.value = theme.lightblue;
});
</script>

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
