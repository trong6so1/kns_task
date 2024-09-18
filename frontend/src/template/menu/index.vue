<template>
    <Menu :model="items" class="w-100" >
        <template #item="{ item, props }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom :class="{ 'bg-dark': !(_isEmpty(item)) }">
                <a v-ripple :href="href" v-bind="props.action" @click="navigate">
                    <span :class="item.icon" />
                    <span class="ml-2">{{ item.label }}</span>
                </a>
            </router-link>
            <a v-else v-ripple :href="item.url" :target="item.target" v-bind="props.action">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
            </a>
        </template>
    </Menu>
</template>

<script setup>
import _isEmpty from "lodash"
import { ref, onMounted } from "vue";
import {field} from './field.js';
import Menu from "primevue/menu"
const items = ref([])

onMounted(() => {
    items.value = field;
    console.log(_isEmpty(items.value)); 
});
</script>

