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
    <div class="test_model_edit">
        <Row type="flex" justify="center" align="middle">
            <Col span="24">
                <h1>
                    test edit
                </h1>


                <Form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                    <FormItem label="string_parameter" prop="string_parameter">
                        <Input v-model="formValidate.string_parameter" placeholder="Enter your string_parameter"></Input>
                    </FormItem>
                    <FormItem label="integer_parameter" prop="integer_parameter">
                        <InputNumber v-model="formValidate.integer_parameter" placeholder="Enter your integer_parameter"></InputNumber>
                    </FormItem>
                    <FormItem>
                        <Button type="primary" @click="handleSubmit('formValidate')">Submit</Button>
                        <Button @click="handleReset('formValidate')" style="margin-left: 8px">Reset</Button>
                    </FormItem>
                </Form>


            </Col>
        </Row>
    </div>
</template>
<script>
    export default {
        data() {
            //
            //app state registration
            this.$store.registerModule('Test_model_edit', {
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

                formValidate: {
                    string_parameter: '',
                    integer_parameter: 0,
                },
                ruleValidate: {
                    string_parameter: [
                        { required: true, message: 'The string_parameter cannot be empty', trigger: 'blur' }
                    ],
                    integer_parameter: [
                        { required: true, type: 'number', message: 'Incorrect integer_parameter format', trigger: 'blur' }
                    ],
                },

            };
        },
        methods: {
            ajax() {
                var self = this;
                return {
                    get(id) {
                        window.axios.get( process.env.MIX_BASE_RELATIVE_URL+'/trident/resource/test_model/'+id ).then(({ data }) => {
                            self.formValidate = data;
                        }).catch(error => {
                            console.log(error);
                        });
                    },
                    update(id,data) {
                        window.axios.put( process.env.MIX_BASE_RELATIVE_URL+'/trident/resource/test_model/'+id,  data ).then((response) => {
                            // Once AJAX resolves we can update the Crud with the new color
                            self.$Message.success('Success!');
                        }).catch(error => {
                            console.log(error);
                        });
                    },
                }
            },
            handleStart () {
                this.$Modal.info({
                    title: 'Bravo',
                    content: 'Now, enjoy the convenience of iView.'
                });
            },
            handleSubmit (name) {
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        
                        // window.axios.get( process.env.MIX_BASE_RELATIVE_URL+'/index.php/trident/resource/test_model' ).then(({ data }) => {
                        //     console.log(data)
                        // });

                        // console.log({
                        //     'this.formValidate.string_parameter': this.formValidate.string_parameter,
                        //     'this.formValidate.integer_parameter': this.formValidate.integer_parameter,
                        // });

                        var formValidate = this.formValidate;

                        this.ajax().update(this.$route.params.id, formValidate);

                    } else {
                        this.$Message.error('Fail!');
                    }
                })
            },
            handleReset (name) {
                this.$refs[name].resetFields();
            }
        },
        mounted() {
            // console.log('test form mounted');
            // console.log({
            //     // 'this.$store': this.$store,
            //     // 'this.$store.state': this.$store.state,
            //     // 'this.$store.state.Index': this.$store.state.Index,
            //     'this.$route': this.$route,
            // });

            this.ajax().get(this.$route.params.id);

        },
    }
</script>
