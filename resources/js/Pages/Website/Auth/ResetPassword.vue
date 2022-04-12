<template>
    <v-row>
        <Head>
            <title>Reset Password</title>
        </Head>

        <v-col md="4" sm="10">
            <v-card>
                <v-card-text>
                    <v-form>
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
                        <v-text-field
                            id="password"
                            label="Password"
                            name="password"
                            prepend-icon="mdi-lock"
                            type="password"
                            v-model="form.password"
                            :error-messages="passwordErrors"
                            @input="$v.form.password.$touch()"
                            @blur="$v.form.password.$touch()"
                        />
                        <v-text-field
                            id="password"
                            label="Password Confirmation"
                            name="password_confirmation"
                            prepend-icon="mdi-lock"
                            type="password"
                            v-model="form.password_confirmation"
                            :error-messages="passwordConfirmationErrors"
                            @input="$v.form.password_confirmation.$touch()"
                            @blur="$v.form.password_confirmation.$touch()"
                        />
                    </v-form>
                </v-card-text>

                <v-card-actions>
                    <v-btn color="primary" dark @click.prevent="reset" :loading="form.processing" depressed>
                        Reset Password
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import {Head} from '@inertiajs/inertia-vue';
    import {email, required} from "vuelidate/lib/validators";

    export default {
        props: {
            email: String,
            token: String,
        },
        components: {
            Head,
        },
        validations: {
            form: {
                email: {required, email},
                password: {required},
                password_confirmation: {required},
            }
        },
        data() {
            return {
                form: this.$inertia.form({
                    token: this.props.token,
                    email: this.props.email,
                    password: '',
                    password_confirmation: '',
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
                !this.$v.form.email.email && errors.push('Email is invalid!');
                return errors
            },
            passwordErrors() {
                const errors = [];
                if (this.$page.props.errors?.password) {
                    errors.push(this.$page.props.errors.password)
                }
                if (!this.$v.form.password.$dirty) {
                    return errors
                }
                !this.$v.form.password.required && errors.push('Password is required!');
                return errors
            },
            passwordConfirmationErrors() {
                const errors = [];
                if (this.$page.props.errors?.password_confirmation) {
                    errors.push(this.$page.props.errors.password_confirmation)
                }
                if (!this.$v.form.password_confirmation.$dirty) {
                    return errors
                }
                !this.$v.form.password_confirmation.required && errors.push('Password confirmation is required!');
                return errors
            },
        },
        methods: {
            reset: () => {
                this.$v.$touch()

                if (this.$v.$invalid) {
                    return
                }

                this.form.post(route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                });
            }
        }
    }
</script>
