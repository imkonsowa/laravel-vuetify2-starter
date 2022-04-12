<template>
    <v-chip
        class="ma-2"
        :color="color"
        label
        outlined
    >
        {{ status }}
    </v-chip>
</template>

<script>
    export default {
        name: "Status",
        props: {
            status: {
                required: true,
                type: String
            }
        },
        computed: {
            color() {
                return {
                    'ACTIVE': 'green',
                    'BLOCKED': 'red',
                    'PENDING': 'orange'
                }[this.status]
            }
        }
    }
</script>

<style scoped>

</style>
