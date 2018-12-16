<style scoped>
    /* @import 'styles/common.css'; */
</style>
<template>
    <div>
        <app-layout>
            <router-view></router-view>
        </app-layout>
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

        },
        methods: {

        }
    }
</script>
