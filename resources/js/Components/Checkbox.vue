<template>
    <input type="checkbox" :value="modelValue" @change="$emit('change', $event.target.value)"
           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
</template>

<script>
    export default {
        props: ['modelValue']
    }
</script>
