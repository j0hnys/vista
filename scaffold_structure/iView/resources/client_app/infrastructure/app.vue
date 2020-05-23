<style scoped>
    /* @import 'styles/common.css'; */
</style>
<template>
    <div>
        <BasicLayout>
            <router-view></router-view>
        </BasicLayout>
    </div>
</template>
<script>
    export default {
        data () {
            return {}
        },
        mounted () {
            console.log('app mounted');
        },
        beforeDestroy () {
            //
        },
        methods: {
            //
        }
    }
</script>
