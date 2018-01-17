<template>
    <img :src="url">
</template>
<script>
    export default {
        props: {
            t: String,
            w: [Number, String],
            h: [Number, String],
            fs: [Number, String],
        },
        computed: {
            url() {
                return route('img', {
                    t: this.t,
                    w: this.w,
                    h: this.h,
                    fs: this.fs
                })
            }
        },
        mounted() {
            // let a = this.$props.filter(pr => pr)
        }
    }
</script>
