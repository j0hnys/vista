<style scoped>
    .layout-con{
        height: 100%;
        width: 100%;
    }
    .menu-item span{
        display: inline-block;
        overflow: hidden;
        width: 69px;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
        transition: width .2s ease .2s;
    }
    .menu-item i{
        transform: translateX(0px);
        transition: font-size .2s ease, transform .2s ease;
        vertical-align: middle;
        font-size: 16px;
    }
    .collapsed-menu span{
        width: 0px;
        transition: width .2s ease;
    }
    .collapsed-menu i{
        transform: translateX(5px);
        transition: font-size .2s ease .2s, transform .2s ease .2s;
        vertical-align: middle;
        font-size: 22px;
    }

    .router-link-exact-active {
        color: white;
    }
        .router-link-exact-active:hover {
            text-decoration: none;
        }
    .router-link-active {
        color: white;
    }
        .router-link-active:hover {
            text-decoration: none;
        }
    .link-style-black {
        color: black;
    }
</style>
<template>
    <div class="layout">
        <Layout :style="{minHeight: '100vh'}">
            <Sider collapsible :collapsed-width="78" v-model="isCollapsed">
                <h1 :style="{color: 'white', position: 'relative', top: '5px', left: '20px'}">Vista</h1>
                <Menu active-name="1-2" theme="dark" width="auto" :class="menuitemClasses">
                    <MenuItem v-for="menu_item in this.$store.state.navigation.main_menu" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="!menu_item.children" :name="menu_item.name">
                        <Icon :type="menu_item.icon_type"></Icon>
                        <router-link :to="menu_item.redirect_url">{{menu_item.text}}</router-link>
                    </MenuItem>

                    <Submenu v-for="menu_item in this.$store.state.navigation.main_menu" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="menu_item.children" :name="menu_item.name">
                        <template slot="title">
                            <Icon :type="menu_item.icon_type"></Icon>
                            <router-link :to="menu_item.redirect_url">{{menu_item.text}}</router-link>
                        </template>
                        <MenuGroup v-for="submenu_group in menu_item.children" v-bind:data="submenu_group" v-bind:key="submenu_group.name" :title="submenu_group.text">
                            <MenuItem v-for="submenu_group_item in submenu_group.children" v-bind:data="submenu_group_item" v-bind:key="submenu_group_item.name" :name="submenu_group_item.name">
                                <router-link :to="submenu_group_item.redirect_url">{{submenu_group_item.text}}</router-link>
                            </MenuItem>
                        </MenuGroup>
                    </Submenu>

                </Menu>
            </Sider>
            <Layout>
                <Header :style="{background: '#fff', boxShadow: '0 2px 3px 2px rgba(0,0,0,.1)'}">Page Title</Header>
                <Content :style="{padding: '0 16px 16px'}">
                    <Breadcrumb :style="{margin: '16px 0'}">
                        <BreadcrumbItem v-for="breadcrumb in this.$store.state.BasicLayout.breadcrumbs" v-bind:data="breadcrumb" v-bind:key="breadcrumb.text">{{breadcrumb.text}}</BreadcrumbItem>
                    </Breadcrumb>
                    <Menu mode="horizontal" theme="light" active-name="1" v-if="submenu">

                        <MenuItem v-for="menu_item in submenu" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="!menu_item.children" :name="menu_item.name">
                            <Icon :type="menu_item.icon_type"></Icon>
                            <router-link class="link-style-black" :to="menu_item.redirect_url">{{menu_item.text}}</router-link>
                        </MenuItem>
                        <Submenu v-for="menu_item in submenu" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="menu_item.children" :name="menu_item.name">
                            <template slot="title">
                                <Icon :type="menu_item.icon_type"></Icon>
                                <router-link class="link-style-black" :to="menu_item.redirect_url">{{menu_item.text}}</router-link>
                            </template>
                            <MenuGroup v-for="submenu_group in menu_item.children" v-bind:data="submenu_group" v-bind:key="submenu_group.name" :title="submenu_group.text">
                                <MenuItem v-for="submenu_group_item in submenu_group.children" v-bind:data="submenu_group_item" v-bind:key="submenu_group_item.name" :name="submenu_group_item.name">
                                    <router-link class="link-style-black" :to="submenu_group_item.redirect_url">{{submenu_group_item.text}}</router-link>
                                </MenuItem>
                            </MenuGroup>
                        </Submenu>

                    </Menu>
                    <Card>
                        <div style="height: 600px">
                            <!-- here we will put the content of the page -->
                            <slot></slot>
                            <!--  -->
                        </div>
                    </Card>
                </Content>
            </Layout>
        </Layout>
    </div>
</template>
<script>
    export default {
        data () {
            //
            //app state registration
            this.$store.registerModule('BasicLayout', {
                state: {
                    breadcrumbs: [
                        {
                            text: 'Home',
                        },
                        {
                            text: 'Components',
                        },
                        {
                            text: 'Layout',
                        },
                    ],
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
                isCollapsed: false,
                submenu: this.$route.meta.submenu,
            };
        },
        computed: {
            menuitemClasses: function () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        },
        mounted() {
            console.log('basic layout mounted');
            console.log({
                'this': this,
                'this.$store.state.BasicLayout.menu_items': this.$store.state.BasicLayout.menu_items,
            });
        },
    }
</script>