<template>
    <section class="modal-card-body">
        <b-field label="Tên"
            :type="form.errors.has('name') ? 'is-danger' : ''"
            :message="form.errors.get('name')">
            <b-input
                icon="id-card"
                v-model="form.name"
                placeholder="Tên của bạn"
                required>
            </b-input>
        </b-field>

        <b-field label="Email"
            :type="form.errors.has('email') ? 'is-danger' : ''"
            :message="form.errors.get('email')">
            <b-input
                icon="envelope"
                type="email"
                v-model="form.email"
                placeholder="Thư điện tử"
                required>
            </b-input>
        </b-field>

        <b-field label="Mật khẩu"
            :type="form.errors.has('password') ? 'is-danger' : ''"
            :message="form.errors.get('password')">
            <b-input
                icon="key"
                type="password"
                v-model="form.password"
                password-reveal
                placeholder="Nhập mật khẩu"
                required>
            </b-input>
        </b-field>

        <b-field label="Nhập lại mật khẩu"
            :type="form.errors.has('password_confirmation') ? 'is-danger' : ''"
            :message="form.errors.get('password_confirmation')">
            <b-input
                icon="key"
                type="password"
                v-model="form.password_confirmation"
                password-reveal
                placeholder="Nhập lại mật khẩu"
                required>
            </b-input>
        </b-field>
    </section>
</template>
<script>
    import Form from 'vform'
    export default {
        data: () => ({
          form: new Form({
            name: '',
            email: '',
            password: '',
            password_confirmation: ''
          })
        }),

        methods: {
          async register () {
            // Register the user.
            const { data } = await this.form.post('register')

            // Log in the user.
            const { data: { token }} = await this.form.post('login')

            // Save the token.
            this.$store.dispatch('saveToken', { token })

            // Update the user.
            await this.$store.dispatch('updateUser', { user: data })

            // Close parent modal
            this.$emit('closeModal')

            // Redirect home.
            this.$router.push({ name: 'welcome' })
          }
        }
    }
</script>
