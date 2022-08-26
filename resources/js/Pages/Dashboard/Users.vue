<template>
    <v-card flat>
        <v-card-title>
            <h3 class="heading">
                Users
            </h3>
        </v-card-title>

        <v-card-text>
            <XDatatable
                ref="usersDatatable"
                :ajax="{
                    url: route('users.datatable'),
                    query: {
                        type: 'USER'
                    }
                }"
                :actions="{
                    enabled: false,
                }"
                :headers="[
                    {text: 'Name', value: 'name'},
                    {text: 'Email', value: 'email'},
                ]"
            />
        </v-card-text>
    </v-card>
</template>

<script>
    import XDatatable from "@/Components/XDatatable";

    export default {
        name: "AdminUsersIndexPage",
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
                .channel('users.created')
                .listen('.user.created', (e) => {
                    this.$refs.usersDatatable.loadData()

                    const notification = new Notification('New user created', {
                        body: 'Please check the newly created user!'
                    })

                    notification.

                    setTimeout(() => {
                        notification.close()
                    }, 5000)
                })
        },
        data: () => ({
            showFormDialog: false,
            showConfirmDeleteDialog: false,
            modelToEdit: {},
            modelToDelete: {},
            editingMode: false,
            posting: false,
        }),
        methods: {
            async deleteModel() {
                if (!this.modelToDelete) {
                    return;
                }

                this.posting = true;

                await axios.post(route('admin.users.delete', {id: this.modelToDelete.id}))
                    .then(() => {
                        this.$refs.usersDatatable.loadData()

                        this.showConfirmDeleteDialog = false;
                        this.modelToDelete = {};
                    }).finally(() => (this.posting = false))
            }
        }
    }
</script>

<style scoped>

</style>
