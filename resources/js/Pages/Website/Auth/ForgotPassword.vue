<template>
    <BreezeGuestLayout>
        <Head>
            <title>Forgot Password</title>
        </Head>

        <v-row align="center" justify="center">
            <v-col cols="10" class="text-center">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                    {{ status }}
                </div>
            </v-col>

            <v-col sm="10" md="4">
                <v-card v-if="!showSuccessCard">
                    <v-card-text>
                        <v-text-field
                            label="Email"
                            name="email"
                            prepend-icon="email"
                            type="email"
                            v-model="form.email"
                            :error-messages="emailErrors"
                            @input="$v.form.email.$touch()"
                            @blur="$v.form.email.$touch()"
                        />
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer/>
                        <v-btn color="primary" dark @click.prevent="submit" :loading="form.processing" depressed>
                            Reset
                        </v-btn>
                    </v-card-actions>
                </v-card>

                <v-card v-else flat>
                    <v-card-text>
                        <v-alert type="success">
                            A reset link sent to your email.
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </BreezeGuestLayout>
</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import BreezeGuestLayout from '@/Layouts/Guest.vue';
    import BreezeInput from '@/Components/Input.vue';
    import BreezeLabel from '@/Components/Label.vue';
    import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
    import {Head} from '@inertiajs/inertia-vue'
    import {required} from 'vuelidate/lib/validators'

    export default {
        props: {
            status: String,
        },
        components: {
            BreezeButton,
            BreezeGuestLayout,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
            Head,
        },
        validations: {
            form: {
                email: {required},
            }
        },
        data() {
            return {
                showSuccessCard: false,
                form: this.$inertia.form({
                    email: ''
                })
            }
        },
        computed: {
            emailErrors() {
                const errors = [];
                if (this.$page.props.errors?.email) {
                    errors.push(this.$page.props.errors.email)
                }
                if (!this.$v.form.email.$dirty) {
                    return errors
                }
                !this.$v.form.email.required && errors.push('Email is required!');
                return errors
            },
        },
        methods: {
            submit() {
                this.form.post(route('password.email'), {
                    onSuccess: () => {
                        this.showSuccessCard = true
                    }
                });
            }
        }
    }
</script>
