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
                <h1 :style="{color: 'white', position: 'relative', top: '5px', left: '28px'}">{{menu_logo}}</h1>
                <Menu ref="main_menu" @on-select="on_main_menu_item_clicked" :active-name="active_menu_name" theme="dark" width="auto" :class="menuitemClasses">
                    <MenuItem v-for="menu_item in this.$store.getters['components/BasicLayout/navigation']()['main_menu']" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="!menu_item.children" :name="menu_item.name">
                        <Icon :type="menu_item.icon_type"></Icon>
                        <router-link :to="menu_item.redirect_url"><span v-if="!isCollapsed" :style="{color: 'white', width: '80%'}">{{menu_item.text}}</span></router-link>
                    </MenuItem>

                    <Submenu v-for="menu_item in this.$store.getters['components/BasicLayout/navigation']()['main_menu']" v-bind:data="menu_item" v-bind:key="menu_item.name" v-if="menu_item.children" :name="menu_item.name">
                        <template slot="title">
                            <Icon :type="menu_item.icon_type"></Icon>
                            <router-link :to="menu_item.redirect_url"><span v-if="!isCollapsed" :style="{color: 'white', width: '65%'}">{{menu_item.text}}</span></router-link>
                        </template>
                        <MenuGroup v-for="submenu_group in menu_item.children" v-bind:data="submenu_group" v-bind:key="submenu_group.name" :title="submenu_group.text">
                            <MenuItem v-for="submenu_group_item in submenu_group.children" v-bind:data="submenu_group_item" v-bind:key="submenu_group_item.name" :name="submenu_group_item.name">
                                <router-link :to="submenu_group_item.redirect_url"><span v-if="!isCollapsed" :style="{color: 'white', width: '100%'}">{{submenu_group_item.text}}</span></router-link>
                            </MenuItem>
                        </MenuGroup>
                    </Submenu>

                </Menu>
            </Sider>
            <Layout>
                <Header :style="{background: '#fff', boxShadow: '0 2px 3px 2px rgba(0,0,0,.1)', padding: '0px 10px'}">
                    <Row> 
                        <Col :xs="21" :sm="21" :md="22" :lg="22">
                            <span :style="{'font-size': '2em'}">{{page_title}}</span>
                        </Col>
                        <Col :xs="3" :sm="3" :md="2" :lg="2">
                            <Col span="12">
                                <Poptip
                                    style="position: relative; top: 5px;"
                                    confirm
                                    title="Want to see all notifications?"
                                    ok-text="yes"
                                    cancel-text="no">
                                    <Badge :count="1" :offset="[15,0]">
                                        <Icon type="ios-notifications-outline" size="26"></Icon>
                                    </Badge>
                                </Poptip>
                            </Col>
                            <Col span="12">
                                <Dropdown @on-click="onLogoutButtonClicked">
                                    <Avatar icon="ios-person" size="large" />
                                    <DropdownMenu slot="list">
                                        <DropdownItem name="logout">Logout</DropdownItem>
                                    </DropdownMenu>
                                </Dropdown>
                            </Col>
                        </Col>
                    </Row>
                </Header>
                <Content :style="{padding: '0 16px 16px'}">
                    <Breadcrumb :style="{margin: '16px 0'}">
                        <BreadcrumbItem v-for="breadcrumb in this.$store.state.components.BasicLayout.breadcrumbs" v-bind:data="breadcrumb" v-bind:key="breadcrumb.text">{{breadcrumb.text}}</BreadcrumbItem>
                    </Breadcrumb>
                    <Menu ref="sub_menu" @on-select="on_sub_menu_item_clicked" mode="horizontal" theme="light" active-name="1" v-if="submenu">

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
        namespace: 'components/BasicLayout',
        name: 'BasicLayout',
        data () {
            var state = {
                breadcrumbs: [],
            };
            if (this.$store.state.components.BasicLayout) 
            {
                state = this.$store.state.components.BasicLayout;
            }

            //
            //component state registration
            return {
                ...state,

                isCollapsed: false,
                submenu: this.$route.meta.submenu,

                page_title: 'Welcome',

                active_menu_name: 1,

                app_title: {
                    visible: 'Vista',
                    collapsed_menu: 'V',
                    expanded_menu: 'Vista',
                },

                main_menu_breadcrumb: [],
                sub_menu_breadcrumb: [],
            };
        },
        computed: {
            menuitemClasses: function () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            },
            menu_logo() {
                if (this.isCollapsed) {
                    return this.app_title.collapsed_menu;
                }
                return this.app_title.expanded_menu;
            },
        },
        methods: {
            on_main_menu_item_clicked(menu_name) {

                var indexes = menu_name.split('-');

                var main_menu = this.$store.getters['components/BasicLayout/navigation']()['main_menu'];
                var current_menu_level = main_menu;

                this.main_menu_breadcrumb = [];
                for (const i in indexes) {
                    this.main_menu_breadcrumb.push({
                        text: current_menu_level[ indexes[i]-1 ].text,
                    });
                    if (current_menu_level[ indexes[i]-1 ].children) {
                        current_menu_level = current_menu_level[ indexes[i]-1 ].children;
                    }
                }

                this.page_title = current_menu_level[ indexes[indexes.length-1]-1 ].text;

                this.$store.commit('components/BasicLayout/set_breadcrumbs',this.main_menu_breadcrumb);

            },
            on_sub_menu_item_clicked(menu_name) {

                var indexes = menu_name.split('-');
                var sub_menu = this.submenu;
                var current_menu_level = sub_menu;

                this.sub_menu_breadcrumb = [];
                for (const i in indexes) {
                    this.sub_menu_breadcrumb.push({
                        text: current_menu_level[ indexes[i]-1 ].text,
                    });
                    if (current_menu_level[ indexes[i]-1 ].children) {
                        current_menu_level = current_menu_level[ indexes[i]-1 ].children;
                    }
                }

                this.page_title = current_menu_level[ indexes[indexes.length-1]-1 ].text;

                this.$store.commit('components/BasicLayout/set_breadcrumbs',this.main_menu_breadcrumb.concat(this.sub_menu_breadcrumb));

            },
            onLogoutButtonClicked(name) {
                if (name == 'logout') {
                    this.$router.push(process.env.MIX_BASE_RELATIVE_URL_BACKEND+'/logout');
                    window.location.reload();
                }
            },
        },
        mounted() {

            var navigation = this.$store.getters['components/BasicLayout/navigation']();

            var current_menu = navigation.main_menu[0];

            for (const i in navigation.main_menu) {
                if (navigation.main_menu.hasOwnProperty(i)) {
                    const element = navigation.main_menu[i];
                    
                    if (this.$router.resolve(this.$route.matched[0].path).href === element.redirect_url) {
                        this.active_menu_name = element.name;
                        current_menu = element;
                    }
                }
            }
            this.page_title = current_menu.text;

            this.$store.commit('components/BasicLayout/set_breadcrumbs',[
                {
                    text: current_menu.text
                }
            ]);

        },
    }
</script>