import {RouterView} from 'vue-router'
import router from '@/router/index.js'

// 登录路由
const LoginRoute = {
  path: '/login',
  name: 'Login',
  component: () => import('@/views/login/LoginView.vue'),
  meta: {
    title: '登录'
  }
}

// 404
const NotFoundRoute = {
  path: '/:pathMatch(.*)*',
  name: 'NotFound',
  component: () => import('@/views/404/NotFoundView.vue'),
  meta: {
    title: '404'
  }
}

const RefreshRoute={
  path: '/refresh',
  name: 'Refresh',
  component: RouterView,
  meta: {
    title: '刷新'
  },
  beforeEnter: (to,from) => {
    // 刷新
    setTimeout(() => {
      router.replace(from.fullPath)
    })
    return true
  }
}
export default [LoginRoute,NotFoundRoute,RefreshRoute]
