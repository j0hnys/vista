<style scoped>

</style>

<template>
    <div class="page1">
        <Row type="flex" justify="center" align="middle">
            <Col span="24">
                <div>
                    This is page 1. variable = {{variable}}
                </div>
            </Col>
            <Col span="24">
                <Button type="primary" @click="onButtonClicked">CLICK ME</Button>
            </Col>
        </Row>
    </div>
</template>

<script>
    export default {
        namespace: 'pages/page1',
        use: {
            mixins: [
                // 'pages/index',
                // 'pages/page111',
                {
                    alias: 'mixinsss',
                    namespace: 'pages/page111',
                },
                {
                    alias: 'mixin_otinanai11',
                    namespace: 'pages/index',
                }
            ]
        },
        name: 'page1',
        data() {
            return {
                //
            };
        },
        computed: {
            variable() {
                return this.$store.state.pages.Page1.variable;
            }
        },
        methods: {
            setVariable () {
                this.$store.dispatch('pages/Page1/setVariable', 'ede');
            },
            onButtonClicked() {
                this.$store.dispatch('pages/Page1/setVariable', 'ede!!!');
            }
        },
        mounted() {
            this.setVariable();

            console.log({
                this: this.$typeChecker.check('models/DTOs/Page1',{
                    variable: '111'
                }),
            });

            console.log(this.mixinsss().testPageMixinFunction());
            console.log(this.mixin_otinanai11().testIndexMixinFunction());
        },
    }
</script>
