<template>
    <form @submit.prevent="submit">
        <div class="modal-card">
            <header class="modal-card-head">
                <b-tabs position="is-centered" class="block login-tabs" expanded v-model="tab">
                    <b-tab-item :label="tab.label" :icon="tab.icon" icon-pack="fa" v-for="(tab, index) in tabs" :key="index"/>
                </b-tabs>
            </header>
            <component :is="activetab.component" ref="child" @closeModal="$parent.close()"></component>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary">{{ activetab.label }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    import Login from './login'
    import  Register from './register'
    export default {
        data: () => ({
            tab: 0,
            tabs: [
                {
                    label: 'Sign in',
                    icon: 'sign-in',
                    component: Login
                },
                {
                    label: 'Sign up',
                    icon: 'user-plus',
                    component: Register
                }
            ]
        }),
        computed: {
            activetab() {
                return this.tabs[this.tab]
            }
        },
        methods: {
            submit() {
                this.tab == 0 ? this.$refs.child.login() : this.$refs.child.register()
            }
        },
        component: {
            Login,
            Register
        }
    }
</script>

<style scoped>
    .modal-card {
        /*width: auto;*/
    }
    .login-tabs {
        margin: auto;
    }
</style>

