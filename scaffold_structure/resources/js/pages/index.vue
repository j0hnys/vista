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
                    test
                </h1>

                <router-link to="/">Home</router-link>

                <h2>
                    <p>Welcome to your iView app!</p>
                    <Button @click="handleStart">Start iView</Button>
                </h2>
            </Col>
        </Row>
    </div>
</template>
<script>
    export default {
        data() {
            //
            //app state registration
            this.$store.registerModule(['pages','Index'], {
                namespaced: true,
                state: {
                    menu_item1: 'merge',
                },
                mutations: {    //must be synchronous!! ta "actions" einai workflows praktika!!
                    set_menu (state, name) {
                        state.menu_item = name;
                    },
                },
            });

            //
            //component state registration
            return {
                local_parameter: 'local'
            };
        },
        methods: {
            handleStart () {
                this.$Modal.info({
                    title: 'Bravo',
                    content: 'Now, enjoy the convenience of iView.'
                });
            }
        },
        mounted() {
            // console.log('index mounted');
            // console.log({
            //     // 'this.$store': this.$store,
            //     'this.$store.state': this.$store.state,
            //     'this.$store.state.Index': this.$store.state.Index,
            // });
        },
    }
</script>
