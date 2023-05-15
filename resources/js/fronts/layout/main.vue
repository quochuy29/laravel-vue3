<template>
    <a-layout has-sider>
        <a-layout-sider v-model:collapsed="collapsed" collapsible :trigger="null" :style="{ overflow: 'hidden', height: '100vh', position: 'sticky', left: 0, top: 0, bottom: 0, paddingTop:'64px' }">
            <a-menu v-model:selectedKeys="selectedKeys" theme="light" mode="inline" :style="{ width: '100%'}">
                <a-menu-item key="calendar">
                    <user-outlined />
                    <span class="nav-text">
                        <router-link @click="checkSelected" to="/calendar">Calendar</router-link>
                    </span>
                </a-menu-item>
                <a-menu-item key="my-request">
                    <video-camera-outlined />
                    <span class="nav-text">
                        <router-link @click="checkSelected" to="/my-request">My request</router-link>
                    </span>
                </a-menu-item>
                <a-menu-item key="my-history">
                    <upload-outlined />
                    <span class="nav-text">
                        <router-link @click="checkSelected" to="/my-history">My history</router-link>
                    </span>
                </a-menu-item>
                <a-menu-item key="4">
                    <bar-chart-outlined />
                    <span class="nav-text">nav 4</span>
                </a-menu-item>
                <a-menu-item key="5">
                    <cloud-outlined />
                    <span class="nav-text">nav 5</span>
                </a-menu-item>
                <a-menu-item key="6">
                    <appstore-outlined />
                    <span class="nav-text">nav 6</span>
                </a-menu-item>
                <a-menu-item key="7">
                    <team-outlined />
                    <span class="nav-text">nav 7</span>
                </a-menu-item>
                <a-menu-item key="8">
                    <shop-outlined />
                    <span class="nav-text">nav 8</span>
                </a-menu-item>
            </a-menu>
            <a-menu :style="{ position: 'absolute',bottom: '0',display: 'flex',flexDirection: 'column',alignItems: 'center',width: '100%'}">
                <a-menu-item :style="{ paddingTop: '0', paddingBottom: '0' }">
                    <menu-unfold-outlined v-if="collapsed" class="trigger" @click="() => (collapsed = !collapsed)" />
                    <menu-fold-outlined v-else class="trigger" @click="() => (collapsed = !collapsed)" />
                </a-menu-item>
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <a-layout-header :style="{ background: '#fff', padding: 0 }" >
                <div class="logo" />
            </a-layout-header>
            <a-layout-content :style="{ margin: '0 16px 0', overflow: 'initial' }">
                <header style="height: 64px;column-gap: 10px;padding-bottom: 10px;"></header>
                <div :style="{ margin: '16px 0', textAlign: 'center' }">
                   <router-view></router-view>
                </div>
            </a-layout-content>
            <a-layout-footer :style="{ textAlign: 'center' }">
                Timesheet ©2023 Created by HuyPQ
            </a-layout-footer>
        </a-layout>
    </a-layout>
</template>

<script>
import { UserOutlined, VideoCameraOutlined, UploadOutlined, BarChartOutlined, CloudOutlined, AppstoreOutlined, TeamOutlined, ShopOutlined, MenuUnfoldOutlined, MenuFoldOutlined } from '@ant-design/icons-vue';
import { defineComponent, ref, onBeforeMount } from 'vue';
import { useRouter } from 'vue-router'
export default defineComponent({
    components: {
        UserOutlined,
        VideoCameraOutlined,
        UploadOutlined,
        BarChartOutlined,
        CloudOutlined,
        AppstoreOutlined,
        TeamOutlined,
        ShopOutlined,
        MenuUnfoldOutlined,
        MenuFoldOutlined
    },
    setup() {
        const route = useRouter();
        const currentRoute = ref(['']);
        onBeforeMount(() => {
            checkSelected();
        });

        const checkSelected = () => {
            currentRoute.value = [route.currentRoute.value.name];
        };

        return {
            collapsed: ref(false),
            selectedKeys: currentRoute,
            checkSelected
        };
    }
});
</script>

<style>
#components-layout-demo-fixed-sider .logo {
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
    margin: 16px;
}

.site-layout .site-layout-background {
    background: #fff;
}

[data-theme='light'] .site-layout .site-layout-background {
    background: #fff;
}

.ant-layout-content {
    margin: 0 16px 0px;
    overflow: initial;
    display: flex;
    flex-direction: column;
    z-index: 100;
    column-gap: 11px;
}

.ant-layout .ant-layout-header {
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    z-index: 9999;
    box-shadow: 0 1px 4px #00152914;
    bottom: 0;
}

.ant-layout-sider-children {
    background: #fff;
    display: flex;
}

.ant-layout-sider-trigger {
    background: #fff !important;
    color: #002140 !important;
}

.logo {
    width: 104px;
    height: 64px;
    display: block;
    position: absolute;
    top: 0;
    left: 24px;
    background: url(../image/logo.png);
}
</style>
