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
    // import app_layout from '../modules/presentation/components/BasicLayout.vue';

    export default {
        // components: {
        //     'app-layout': app_layout,
        // },
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
