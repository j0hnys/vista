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
    import app_layout from './components/BasicLayout.vue';

    export default {
        components: {
            'app-layout': app_layout,
        },
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
