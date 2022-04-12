<template>
    <v-row>
        <v-col :cols="12" :sm="12" :md="3" v-for="key in Object.keys($page.props.statistics)" :key="key" v-if="statisticsMap[key]">
            <v-card hover @click="visit(statisticsMap[key].route)">
                <v-card-title>
                    <h3 class="accent--text">
                        {{ statisticsMap[key].title }}
                    </h3>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col :cols="2" :sm="2">
                            <v-icon color="primary" x-large>{{ statisticsMap[key].icon }}</v-icon>
                        </v-col>
                        <v-spacer/>
                        <v-col :cols="12" :sm="3">
                            <h1>{{ $page.props.statistics[key] }}</h1>
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
        },
        data: function () {
            return {
                statisticsMap: {
                    users_count: {
                        icon: 'people',
                        route: route('users'),
                        title: 'Users'
                    },
                }
            }
        }
    }
</script>
