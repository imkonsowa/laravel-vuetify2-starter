<template>
    <v-dialog
        v-model="show"
        persistent
        :max-width="maxWidth"
        no-click-animation
    >
        <v-card>
            <v-card-title>{{ text }}</v-card-title>

            <v-divider/>

            <v-card-text>
                <slot/>
            </v-card-text>

            <v-card-actions v-if="showActions">
                <v-spacer></v-spacer>
                <v-btn
                    color="green darken-1"
                    depressed rounded outlined
                    @click="$emit('close')"
                    :disabled="loading"
                    dark
                >
                    Cancel
                </v-btn>
                <v-btn
                    color="primary"
                    depressed rounded outlined
                    @click="$emit('confirm')"
                    :loading="loading"
                    dark
                >
                    {{ confirmText || 'Confirm' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

</template>

<script>
    export default {
        name: "ConfirmDialog",
        props: {
            show: {
                required: true,
                type: Boolean,
            },
            loading: {
                required: false,
                type: Boolean,
                default: false
            },
            text: {
                required: true,
                type: String,
            },
            confirmText: {
                required: false,
                type: String,
            },
            showActions: {
                required: false,
                type: Boolean,
                default: true
            },
            maxWidth: {
                required: false,
                default: 600,
                type: Number
            }
        }
    }
</script>

<style scoped>

</style>
