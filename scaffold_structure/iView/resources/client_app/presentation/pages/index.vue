<style scoped>
    .index {
        width: 100%;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        text-align: center;
    }
        .index h1 {
            height: 150px;
        }
            .index h1 img {
                height: 100%;
            }
        .index h2 {
            color: #666;
            margin-bottom: 200px;
        }
            .index h2 p {
                margin: 0 0 50px;
            }
    .ivu-row-flex {
        height: 100%;
    }
</style>

<template>
    <div class="index">
        <Row type="flex" justify="center" align="middle">
            <Col span="24">
                <h1>
                    Hi!
                </h1>

                <router-link :to="{name:'/'}">Home</router-link>

                <h2>
                    <p>Welcome to your Trident-Vista app!</p>
                </h2>
            </Col>
        </Row>
    </div>
</template>

<script>
    export default {
        namespace: 'pages/index',
        use: [
            {
                alias: 'demo_mixin',
                namespace: 'mixins/index',
            }
        ],
        name: 'index',
        data() {
            var local = {
                variable: '',
            };

            return {
                ...local,
            };
        },
        types: {
            namespace: 'models/Types/Index',
            schema() {
                return new Type({
                    variable: String,
                });
            }
        },
        methods: {
            handleStart () {
                this.$Modal.info({
                    title: 'Bravo',
                    content: 'Now, enjoy the convenience of iView.'
                });

                this.$globalEvents.$emit('pages/Index/test','emited string');

            }
        },
        mounted() {
            this.$globalEvents.$on('pages/Index/test',(data) => {
                console.log('pages/Index/test global event fired with data: "'+data+'"');    
            });

            this.$typeChecker.check('models/Types/Index',{
                variable: '1',
            });

            console.log(this.demo_mixin.getSimpleString());
        },
    }
</script>
