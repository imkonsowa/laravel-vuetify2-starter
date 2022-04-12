<template>
    <v-row no-gutters align="center" justify="center">
        <Head>
            <title>Log in</title>
        </Head>

        <v-col :md="4" :sm="10">
            <v-card>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            label="email"
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

                        <v-checkbox
                            v-model="form.remember"
                            label="Remember me"
                            :false-value="false"
                            :true-value="true"
                        ></v-checkbox>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" dark @click.prevent="submit" :loading="form.processing" depressed>
                        Login
                    </v-btn>
                    &nbsp; or
                    <v-btn text color="primary" @click="visit('/register')">
                        Register
                    </v-btn>
                    <v-spacer/>
                    <v-btn text color="primary" @click="visit('/forgot-password')">
                        Reset Password
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
    import BreezeButton from '@/Components/Button.vue';
    import BreezeCheckbox from '@/Components/Checkbox.vue';
    import BreezeGuestLayout from '@/Layouts/Guest.vue';
    import BreezeInput from '@/Components/Input.vue';
    import BreezeLabel from '@/Components/Label.vue';
    import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
    import {Head, Link} from '@inertiajs/inertia-vue';
    import {required} from 'vuelidate/lib/validators'

    export default {
        props: {
            canResetPassword: Boolean,
            status: String,
        },
        components: {
            BreezeButton,
            BreezeCheckbox,
            BreezeGuestLayout,
            BreezeInput,
            BreezeLabel,
            BreezeValidationErrors,
            Head,
            Link,
        },
        validations: {
            form: {
                email: {required},
                password: {required}
            }
        },
        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
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
            }
        },
        methods: {
            submit() {
                this.form.post(route('login'), {
                    onFinish: () => this.form.reset('password'),
                });
            }
        }
    }
</script>
