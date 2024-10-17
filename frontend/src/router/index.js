/*
 * @Description: 
 * @Date: 2024-09-29 08:16:30
 * @LastEditTime: 2024-09-29 16:31:52
 * @FilePath: \dev1.1\src\router\index.js
 */
import {createRouter,createWebHistory} from 'vue-router';
import {routerGuard} from '@/router/guards.js';
import RootRoute from '@/router/rootRoute.js';
import outsideLayoutRoute from '@/router/outsideLayoutRoute.js';

// 确保 RootRoute 被添加到路由配置中
const routes=[RootRoute,...outsideLayoutRoute];

const router=createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

export const setupRouter=(app) => {
  routerGuard(router);
  app.use(router);
};

export default router;
