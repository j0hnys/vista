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
    <div class="test_model_list">
        <Row type="flex" justify="center" align="middle">
            <Col span="24">

                <Row type="flex" justify="end" style="margin-bottom: 20px;">
                    <Col>
                        <Button type="primary" @click="on_create_button_clicked">Create</Button>
                    </Col>
                </Row>

                <Row>
                    <Col>
                        <Table border :columns="columns" :data="data"></Table>
                     </Col>
                </Row>

            </Col>
        </Row>
    </div>
</template>
<script>
    export default {
        data() {
            //
            //app state registration
            this.$store.registerModule('Test_model_list', {
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
                local_parameter: 'local',


                columns: [
                    {
                        title: 'string_parameter',
                        key: 'string_parameter'
                    },
                    {
                        title: 'integer_parameter',
                        key: 'integer_parameter'
                    },
                    {
                        title: 'Action',
                        key: 'action',
                        width: 150,
                        align: 'center',
                        render: (h, params) => {
                            var row = params.row;

                            // console.log({
                            //     params: params,
                            // });

                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    style: {
                                        marginRight: '5px'
                                    },
                                    on: {
                                        click: () => {
                                            // this.show(params.index)
                                            this.$router.push({ name: 'test_model_update', params: { id: row.id } });
                                        }
                                    }
                                }, 'Edit'),
                                h('Button', {
                                    props: {
                                        type: 'error',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            // this.remove(params.index)
                                            // this.$router.push({ name: '/vista-framework/public/' });
                                            console.log('delete clicked!');

                                            this.ajax().delete(row.id);

                                        }
                                    }
                                }, 'Delete')
                            ]);
                        }
                    }
                ],
                data: []


            };
        },
        methods: {
            ajax() {
                var self = this;
                return {
                    get() {
                        window.axios.get( process.env.MIX_BASE_RELATIVE_URL+'/trident/resource/test_model' ).then(({ data }) => {
                            // console.log(data);
                            self.data = data;
                        }).catch(error => {
                            console.log(error);
                        });
                    },
                    delete(id) {
                        window.axios.delete( process.env.MIX_BASE_RELATIVE_URL+'/trident/resource/test_model/'+id ).then(({ data }) => {
                            // console.log(data);
                            window.location.reload();
                        }).catch(error => {
                            console.log(error);
                        });
                    }
                }
            },
            on_create_button_clicked() {
                this.$router.push({ name: 'test_model_create' });
            },
            handleStart () {
                this.$Modal.info({
                    title: 'Bravo',
                    content: 'Now, enjoy the convenience of iView.'
                });
            },
        },
        mounted() {

            // this.$store.state.BasicLayout.commit('set_page_title', 'test');
            this.$store.state.BasicLayout.page_title = 'test';

            // console.log('test list mounted');
            // console.log({
            //     // 'this.$store': this.$store,
            //     // 'this.$store.state': this.$store.state,
            //     // 'this.$store.state.Index': this.$store.state.Index,
            //     'this.$route': this.$route,
            // });

            this.ajax().get();

        },
    }
</script>
