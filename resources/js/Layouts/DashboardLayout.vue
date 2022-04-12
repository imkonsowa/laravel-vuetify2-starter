<template>
    <v-theme-provider>
        <v-app class="dashboard">
            <v-app-bar
                :clipped-left="false"
                fixed
                app
                flat
                color="#FFFFFF"
            >
                <v-app-bar-nav-icon @click.stop="drawer = !drawer">
                    <v-icon color="primary">menu</v-icon>
                </v-app-bar-nav-icon>

                &ensp;&ensp;
                <v-btn depressed rounded color="primary" @click="visit('/')">
                    <v-icon>home</v-icon>
                    Home
                </v-btn>

                <v-spacer/>

                <v-btn depressed outlined color="primary" @click="logout" dark>
                    Logout
                    <v-icon>logout</v-icon>
                </v-btn>
            </v-app-bar>

            <v-theme-provider dark>
                <v-navigation-drawer
                    class="app-sidebar"
                    color="#1E1E2D"
                    v-model="drawer"
                    :clipped="false"
                    fixed
                    app
                >
                    <v-list-item style="height: 64px">
                        <v-list-item-content class="text-center">
                            <v-list-item-title class="title">
                                Admin Dashboard
                            </v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>

                    <v-divider></v-divider>
                    <v-list dense>
                        <template v-for="item in items" v-if="!item.hasOwnProperty('show') || (item.hasOwnProperty('show') && item.show)">
                            <template v-if="item.items">
                                <v-list-group
                                    :key="item.title"
                                    :prepend-icon="item.icon"
                                    no-action
                                    active-class="white--text"
                                    :value="item.open"
                                >
                                    <template v-slot:activator>
                                        <v-list-item-content>
                                            <v-list-item-title v-text="item.title"></v-list-item-title>
                                        </v-list-item-content>
                                    </template>

                                    <v-list-item
                                        v-for="subItem in item.items"
                                        :key="subItem.title"
                                        exact
                                        active-class="white--text"
                                        @click="redirect(subItem.to)"
                                    >
                                        <v-list-item-content class="white--text">
                                            <v-list-item-title v-text="subItem.title"></v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-group>
                            </template>

                            <template v-else>
                                <v-list-item
                                    :key="item.title"
                                    exact
                                    active-class="white--text"
                                    @click="redirect(item.to)"
                                >
                                    <v-list-item-icon>
                                        <v-icon>{{ item.icon }}</v-icon>
                                    </v-list-item-icon>
                                    <v-list-item-content class="white--text">
                                        <v-list-item-title class="white--text">
                                            {{ item.title }}
                                        </v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </template>
                    </v-list>
                </v-navigation-drawer>
            </v-theme-provider>

            <v-main style="background-color: #ECEEF6">
                <v-container fluid>

                    <slot/>
                </v-container>
            </v-main>

            <Snackbar/>
        </v-app>
    </v-theme-provider>
</template>

<script>
    import {Inertia} from '@inertiajs/inertia'
    import Snackbar from "@/Components/Snackbar";
    import ErrorsAlert from "@/Components/ErrorsAlert";

    export default {
        components: {
            Snackbar,
            ErrorsAlert
        },
        data() {
            return {
                drawer: true,
                items: [
                    {
                        icon: 'dashboard',
                        title: 'Dashboard',
                        to: '/dashboard',
                    },
                    {
                        icon: 'people',
                        title: 'Users',
                        to: '/users',
                    },
                ],
                miniVariant: false,
                right: true,
                rightDrawer: false,
                title: 'Admin Dashboard',
                loggingOut: false
            }
        },
        methods: {
            redirect(url) {
                Inertia.visit(url)
            },
            async logout() {
                this.loggingOut = true

                await axios.post(route('logout'))
                    .then(() => {
                        window.location = '/'
                    })
            }
        }
    }
</script>

<style lang="scss">
    .v-list-item:hover {
        background-color: #666666;
    }

    .v-file-input {
        .v-input__slot {
            cursor: pointer !important;
        }
    }

    .v-card__title {
        .heading {
            font-weight: 500 !important;
        }
    }
</style>
