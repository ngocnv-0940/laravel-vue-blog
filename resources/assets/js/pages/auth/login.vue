<template>
    <section class="modal-card-body">
        <b-field label="Email hoặc username"
            :type="form.errors.has('email') ? 'is-danger' : ''"
            :message="form.errors.get('email')">
            <b-input
                icon="envelope"
                v-model="form.email"
                placeholder="Email hoặc username của bạn"
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
                placeholder="Mật khẩu của bạn"
                required>
            </b-input>
        </b-field>
        <b-checkbox v-model="remember">Ghi nhớ đăng nhập</b-checkbox>
        <span @click="closeModal">
            <router-link :to="{ name: 'password.request' }" class="is-pulled-right">
              {{ $t('forgot_password') }}
            </router-link>
        </span>
    </section>
</template>
<script>
    import Form from 'vform'
    export default {
        data: () => ({
          form: new Form({
            email: '',
            password: ''
          }),
          remember: true
        }),
        methods: {
            async login () {
              // Submit the form.
              const { data } = await this.form.post('login')

              // Save the token.
              this.$store.dispatch('saveToken', {
                token: data.token,
                remember: this.remember
              })

              // Fetch the user.
              await this.$store.dispatch('fetchUser')

              // Close parent modal
              this.closeModal()

              // Open success message
              this.$toast.open({
                message: "Đăng nhập thành công",
                type: "is-success"
              })

              // Redirect home.
              // this.$router.push({ name: 'home' })
            },
            closeModal() {
                this.$emit('closeModal')
            }
        }
    }
</script>
