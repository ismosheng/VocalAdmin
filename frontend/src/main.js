import {createApp} from 'vue';
import {setupPlugins} from '@/plugins';
import {setupStore} from '@/store';
import {setupRouter} from '@/router';
import dayjs from 'dayjs';
import App from '@/App.vue';
import permission from './directives/permission'
async function bootstrap() {
    const app=createApp(App);
    app.directive('permission',permission)
    dayjs.locale('zh-cn');

    // 初始化插件
    setupPlugins(app);

    // 初始化路由
    const router=await setupRouter(app); // 确保 setupRouter 返回 router 实例

    // 初始化 Pinia 并将 router 传递给 Pinia store
    await setupStore(app,router); // 修改 setupStore 以接收 router

    app.use(router); // 使用 router

    app.mount('#app');
}

// Call the bootstrap function to initialize the app
bootstrap().catch((error) => {
    console.error('初始化失败:',error);
});
