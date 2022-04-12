<template>
    <v-avatar>
        <img
            v-if="item[propertyName]"
            :src="item[propertyName]"
        >
    </v-avatar>
</template>

<script>
    export default {
        name: "XDatatableAvatar",
        props: {
            item: {
                required: true,
                type: Object
            },
            propertyName: {
                type: String,
                required: true
            }
        },
    }
</script>

<style scoped>

</style>
