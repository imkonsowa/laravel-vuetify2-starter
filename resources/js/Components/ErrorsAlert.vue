<template>
    <v-alert type="error" class="errors-alert" v-if="$store.state.validationErrorsDialog.show">
        Please check the below invalid data:
        <ul v-for="error in $store.state.validationErrorsDialog.errors">
            <li>{{ error.join(' - ') }}</li>
        </ul>
    </v-alert>
</template>

<script>
    export default {
        name: "ErrorsAlert"
    }
</script>

<style scoped>

</style>
