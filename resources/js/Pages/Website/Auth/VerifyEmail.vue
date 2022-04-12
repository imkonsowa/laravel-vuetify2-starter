<template>
    <v-row no-gutters align="center" justify="center">
        <Head>
            <title>Email Verification</title>
        </Head>

        <v-card>
            <v-card-text>
                <p>
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on
                    the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>

                <v-alert color="success" v-if="verificationLinkSent">
                    A new verification link has been sent to the email address you provided during registration.
                </v-alert>
            </v-card-text>

            <v-card-actions>
                <v-btn color="primary" dark @click.prevent="verify" :loading="form.processing" depressed>
                    Resend Verification Email
                </v-btn>
                <v-spacer/>
                <v-btn>
                    <Link :href="route('logout')" method="post" class="underline text-sm text-gray-600 hover:text-gray-900">
                        Log Out
                    </Link>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-row>
</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import BreezeGuestLayout from '@/Layouts/Guest.vue';
    import {Head, Link} from '@inertiajs/inertia-vue';


    export default {
        components: {
            BreezeButton,
            BreezeGuestLayout,
            Head,
            Link
        },
        props: {
            status: String,
        },
        data() {
            return {
                form: this.$inertia.form({})
            }
        },
        computed: {
            verificationLinkSent() {
                return this.status === 'verification-link-sent'
            }
        },
        methods: {
            verify() {
                this.form.post(route('verification.send'))
            }
        }

    }
</script>
