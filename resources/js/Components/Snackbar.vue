<template>
    <v-snackbar
        v-model="snackbar"
        :multi-line="multiLine"
        top
        color="primary"
        timeout="3000"
    >
        {{ snackbarText }}

        <template v-slot:action>
            <v-btn
                outlined rounded depressed
                @click="snackbar = false"
            >
                Close
            </v-btn>
        </template>
    </v-snackbar>
</template>

<script>
    export default {
        name: 'Snackbar',
        data: () => ({
            multiLine: true,
        }),
        computed: {
            snackbar: {
                get() {
                    return this.$store.getters['snackbar']
                },
                set(value) {
                    this.$store.commit('SET_SNACKBAR', false)
                }
            },
            snackbarText: {
                get() {
                    return this.$store.getters['snackbarText']
                },
            }
        },
    }
</script>

<style scoped>

</style>
