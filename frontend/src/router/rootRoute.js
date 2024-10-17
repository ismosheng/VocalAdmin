import { RouterView } from 'vue-router'
import router from '@/router/index.js'

const LayoutDefault = () => import('@/layouts/LayoutDefault.vue')

// 刷新
const RefreshRoute = {
  path: '/refresh',
  name: 'Refresh',
  component: RouterView,
  meta: {
    title: '刷新'
  },
  beforeEnter: (to, from) => {
    // 刷新
    setTimeout(() => {
      router.replace(from.fullPath)
    })
    return true
  }
}


// 跟路由
/**
 * meta 属性
 * title: 路由标题
 * icon: 路由图标
 * namePath： 路由名称路径（当前路由namePath 祖先name集合）
 * outsideLink：是否外链 (window.open) 起一个新标签页
 * iframe：iframe内嵌
 * */

const RootRoute={
  path: '/',
  redirect: '/dashboard',
  name: 'Layout',
  component: LayoutDefault,
  meta: {
    title: '根路由',
    icon: 'material-symbols:account-tree-outline-rounded'
  },
  children: [

  ]
}
export default RootRoute

//const RootRoute = {
// path: '/',
// redirect: '/dashboard',
// name: 'Layout',
// component: LayoutDefault,
// meta: {
//   title: '根路由',
//   icon: 'material-symbols:account-tree-outline-rounded'
// },
// children: [
//   {
//     path: '/dashboard',
//     redirect: '/dashboard/workbench',
//     name: 'Dashboard',
//     dynamic: true,
//     meta: {
//       title: '仪表盘',
//       icon: 'material-symbols:dashboard-outline',
//       namePath: ['Dashboard']
//     },
//     children: [
//       {
//         path: 'workbench',
//         name: 'Workbench',
//         dynamic: true,
//         component: () => import('@/views/workbench/workbenchView.vue'),
//         meta: {
//           title: '工作台',
//           icon: 'icon-park-outline:workbench',
//           namePath: ['Dashboard', 'Workbench']
//         }
//       }
//     ]
//   },
    
//   {
//     path: '/goods',
//     name: 'Goods',
//     dynamic: true,
//     meta: {
//       title: '商品信息',
//       icon: 'grommet-icons:product-hunt',
//       namePath: ['About']
//     },
//     children: [
//       {
//         path: 'info',
//         name: 'GoodsInfo',
//         dynamic: true,
//         component: () => import('@/views/goods/info.vue'),
//         meta: {
//           title: '商品信息维护',
//           icon: 'fluent-mdl2:product-list',
//           namePath: ['Goods', 'GoodsInfo']
//         }
//       },
//       {
//         path: 'list',
//         name: 'GoodsList',
//         dynamic: true,
//         component: () => import('@/views/goods/list.vue'),
//         meta: {
//           title: '商品列表',
//           icon: 'fluent-mdl2:product-list',
//           namePath: ['Goods', 'GoodsList']
//         }
//       }
//     ]
//   },
//   {
//     path: '/about',
//     name: 'About',
//     dynamic: true,
//     component: () => import('@/views/about/AboutView.vue'),
//     meta: {
//       title: '关于',
//       icon: 'material-symbols:info-outline-rounded',
//       namePath: ['About']
//     }
//   },

//   RefreshRoute
// ]
//}

//export default RootRoute

