<template>
    <v-card flat>
        <v-card-title>
            <h3 class="heading">
                Tickets
            </h3>
        </v-card-title>

        <v-card-text>
            <XDatatable
                ref="ticketsDatatable"
                :ajax="{
                    url: route('admin.tickets.datatable'),
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
    </v-card>
</template>

<script>
    import XDatatable from "@/Components/XDatatable";

    export default {
        name: "Tickets",
        components: {
            XDatatable,
        },
        mounted() {
            Notification.requestPermission(res => {
                if (res === 'denied') {
                    alert("Permission is denied")
                }
            })

            Echo
                .channel('tickets.created')
                .listen('.ticket.created', e => {
                    this.$refs.ticketsDatatable.loadData()

                    const notification = new Notification('New ticket submitted', {
                        body: 'Please check the newly submitted ticket!'
                    })

                    notification.

                    setTimeout(() => {
                        notification.close()
                    }, 5000)
                })
        }

    }
</script>

<style scoped>

</style>
