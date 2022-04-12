<template>
    <v-row no-gutters align-content="center" justify="center">
        <Head>
            <title>Register</title>
        </Head>

        <v-col md="4" sm="10">
            <v-card>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            label="Name"
                            name="name"
                            prepend-icon="person"
                            type="email"
                            v-model="form.name"
                            :error-messages="nameErrors"
                            @input="$v.form.name.$touch()"
                            @blur="$v.form.name.$touch()"
                        />

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
                            label="Phone"
                            prepend-icon="phone"
                            type="tel"
                            v-model="form.phone"
                            :error-messages="phoneErrors"
                            @input="$v.form.phone.$touch()"
                            @blur="$v.form.phone.$touch()"
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
                    <v-btn color="primary" dark @click.prevent="register" :loading="form.processing" depressed>
                        Register
                    </v-btn>
                    <v-spacer/>
                    <v-btn text color="primary">
                        <Link :href="route('login')">
                            Already have account?
                        </Link>
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import {Head, Link} from '@inertiajs/inertia-vue';
    import BreezeGuestLayout from '@/Layouts/Guest.vue';
    import {required, email} from 'vuelidate/lib/validators'

    export default {
        name: "Register",
        components: {
            BreezeGuestLayout,
            Link,
            Head,
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: '',
                })
            }
        },
        validations: {
            form: {
                name: {required},
                email: {required, email},
                phone: {required},
                password: {required},
                password_confirmation: {required},
            }
        },
        computed: {
            nameErrors() {
                const errors = [];
                if (this.$page.props.errors?.name) {
                    errors.push(this.$page.props.errors.name)
                }
                if (!this.$v.form.name.$dirty) {
                    return errors
                }
                !this.$v.form.name.required && errors.push('Name is required!');
                return errors
            },
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
            phoneErrors() {
                const errors = [];
                if (this.$page.props.errors?.phone) {
                    errors.push(this.$page.props.errors.phone)
                }

                if (!this.$v.form.phone.$dirty) {
                    return errors
                }
                !this.$v.form.phone.required && errors.push('Phone is required!');
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
            async register() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return
                }

                this.form.post(this.route('register'), {
                    onSuccess: () => {
                        this.form.reset('password')
                        this.form.reset('password_confirmation')

                        this.$v.$reset()

                        this.$inertia.get(route('verification.notice'))
                    },
                })
            }
        }
    }
</script>
