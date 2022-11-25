<template>
    <v-card flat>
        <v-card-title>
            <h3 class="heading">
                Tickets
            </h3>
            <v-spacer/>
            <v-btn
                color="primary"
                rounded
                outlined
                @click="showForm = true"
            >
                Add Ticket <v-icon>add</v-icon>
            </v-btn>
        </v-card-title>

        <v-card-text>
            <XDatatable
                ref="ticketsDatatable"
                :ajax="{
                    url: route('users.tickets.datatable'),
            }"
                :actions="{
                    enabled: true,
            }"
                :headers="[
                {text: 'Title', value: 'title'},
                {text: 'Opened At', value: 'created_at'},
            ]"
            />
        </v-card-text>

        <ConfirmDialog
            :show="showForm"
            :show-actions="false"
            text="Add new ticket"
        >
            <v-card flat>
                <v-card-title>Please fill all inputs</v-card-title>

                <v-card-text>
                    <v-text-field
                        dense
                        outlined
                        label="Title*"
                        v-model="form.title"
                    />

                    <v-textarea
                        dense
                        outlined
                        label="Ticket description"
                        v-model="form.description"
                    ></v-textarea>
                </v-card-text>

                <v-card-actions>
                    <v-btn @click="showForm = false" :disabled="posting">Cancel</v-btn>
                    <v-btn
                        @click="create"
                        color="primary"
                        :loading="posting"
                    >Create</v-btn>
                </v-card-actions>
            </v-card>
        </ConfirmDialog>
    </v-card>
</template>

<script>
    import XDatatable from "@/Components/XDatatable";
    import ConfirmDialog from "@/Components/ConfirmDialog";

    const formData = () => ({
        title: '',
        description: ''
    })

    export default {
        name: "Tickets",
        components: {
            XDatatable,
            ConfirmDialog
        },
        data() {
            return {
                showForm: false,
                form: formData(),
                posting: false,
            }
        },
        methods: {
            create() {
                this.posting = true

                axios.post(route('users.tickets.create'), this.form)
                    .then(response => {
                    this.showForm = false
                    this.$refs.ticketsDatatable.loadData()
                        this.form = {...formData()}
                })
                    .finally(() => this.posting = false)
            }
        }
    }
</script>

<style scoped>

</style>
