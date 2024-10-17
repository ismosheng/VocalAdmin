/*
 * @Description: 
 * @Date: 2024-09-29 08:16:30
 * @LastEditTime: 2024-09-30 08:12:32
 * @FilePath: \dev1.1\src\router\guards.js
 */
import NProgress from 'nprogress'
import { useSystemStore } from '@/store/layout/system.js'
import { useLayoutThemeStore } from '@/store/layout/layoutTheme.js'
import { useUserStore } from '@/store/business/user.js'
import {jwtDecode} from 'jwt-decode';

const getComponentName = (route) => {
  return route.matched
    .map((item) => {
      if (!item.meta?.keepAlive || item.redirect) return
      return item.name
    })
    .filter(Boolean)
}
//const getComponentName = (route) => {
//  .map((item) => {
//    return item.name
//  })
//    .filter(Boolean)
//}

export const routerGuard = (router) => {
  router.beforeEach(async (to,from,next) => {
    const layoutThemeStore=useLayoutThemeStore();
    if(layoutThemeStore.layoutSetting.showProgress) {
      NProgress.start();
    }
    const userStore=useUserStore();
    
    if(!window.isRouteInited && userStore.userToken) {
      await userStore.init();
      window.isRouteInited=true;
      next(to.path);
      return
    }
    // 检查用户是否登录且 token 是否存在
    if(!userStore.userInfo||!userStore.userToken) {
      // 用户未登录
      if(to.path!=='/login') {
        next({path: '/login'});
      } else {
        next();
      }
    } else {
      // 用户已登录，检查 token 是否过期
      const decodedToken=jwtDecode(userStore.userToken);
      const currentTime=Date.now()/1000;
      if(decodedToken.exp<currentTime) {
        
        try {
          await userStore.logout();
          next();
        } catch(error) {
          console.error('Logout failed:',error);
          next(false); // 停止路由导航
        }
      } else {
        // Token 有效
        if(to.path==='/login') {
          next({path: '/'}); // 如果已登录且目标路径是登录页，则重定向到首页
        } else {
          next(); // 继续到目标路径
        }
      }
    }
  });

  router.afterEach((to) => {
    const layoutThemeStore = useLayoutThemeStore()
    if (layoutThemeStore.layoutSetting.showProgress) {
      NProgress.done()
    }

    const systemStore = useSystemStore()
    const toComponentName = getComponentName(to)
    // console.log(toComponentName, '========')
    if (to.meta?.keepAlive) {
      if (toComponentName) {
        systemStore.addKeepAliveList(toComponentName)
      } else {
        console.warn(
          `${to.fullPath}页面组件的keepAlive为true但未设置组件名，会导致缓存失效，请检查`
        )
      }
    } else {
      if (toComponentName) {
        systemStore.removeKeepAliveList(toComponentName)
      }
    }
    // console.log('路由跳转', to, from, failure)
  })

  router.onError(() => {
    // console.error('路由错误', error)
  })
}
