<template>
    <input
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        :value="modelValue"
        @input="$emit('change', $event.target.value)"
        ref="input"
    >
</template>

<script>
    export default {
        props: ['modelValue'],

        mounted() {
            if (this.$refs.input.hasAttribute('autofocus')) {
                this.$refs.input.focus();
            }
        }
    }
</script>
