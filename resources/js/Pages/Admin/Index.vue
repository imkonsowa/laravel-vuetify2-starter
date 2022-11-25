<template>
    <v-row>
        <v-col :cols="12" :sm="12" :md="3">
            <v-card hover @click="visit('users')">
                <v-card-title>
                    <h3 class="accent--text">
                        Users
                    </h3>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col :cols="2" :sm="2">
                            <v-icon color="primary" x-large>people</v-icon>
                        </v-col>
                        <v-spacer/>
                        <v-col :cols="12" :sm="3">
                            <h1>{{ statistics.users_count }}</h1>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: 'AdminDashboardPage',
        props: {
            statistics: {
                required: true,
                type: Object
            }
        }
    }
</script>
