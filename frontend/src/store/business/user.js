import {defineStore} from 'pinia'
import {ref} from 'vue'
import {useRouter,useRoute} from 'vue-router'
import {useSystemStore} from '@/store/layout/system.js'
import {fetchUserInfo} from '@/api/user' // 保留这个函数以应对需要单独更新用户信息的情况
import {menuTreeToRoutes} from '@/router/dynamicRoutes'
import {fetchMenuData} from '@/api/auth' //获取菜单数据

export const useUserStore=defineStore(
  'user',
  () => {
    const userInfo=ref({})
    const userToken=ref('')
    const buttonList=ref([])
    const router=useRouter()
    const route=useRoute()

    const setUserInfo=(data) => {
      userInfo.value=data
    }

    const setUserToken=(token) => {
      userToken.value=token
    }

    const handleDynamicRoutes=async (menuTree) => {
      // 将菜单树转换为路由配置
      const dynamicRoutes=menuTreeToRoutes(menuTree);
      
      dynamicRoutes.forEach((route) => {
        route.meta={...(route.meta||{}),dynamic: true};
        router.addRoute('Layout',route); // 确保添加到 'Layout' 下
      });
      // 导航到当前路由，以触发动态路由的加载
      router.replace(route.fullPath)
    }



    const login=async (data) => {
      setUserToken(data.token)
      setUserInfo(data.user) // 直接使用登录响应中的用户信息
      const menuData=await fetchMenuData()
      buttonList.value=menuData.data.perms||[]
      
      await handleDynamicRoutes(menuData.data.menus||[]) 
      
      // 导航到首页或其他页面
      router.push('/') // 导航到首页或其他页面
    }

    const logout=async () => {
      const systemStore=useSystemStore()

      userInfo.value={}
      userToken.value=''

      localStorage.clear()
      sessionStorage.clear()

      systemStore.closeAllTabs()
      systemStore.clearKeepAliveList()

      // 移除所有动态路由
      router.getRoutes().forEach((route) => {
        if(route.meta&&route.meta.dynamic) {
          router.removeRoute(route.name)
        }
      })

      await router.push('/login')
    }

    // 保留一个方法以单独获取用户信息，以应对特殊情况
    const getUserInfo=async () => {
      try {
        const data=await fetchUserInfo()
        setUserInfo(data.data.user)
        buttonList.value=menuData.data.perms||[]
        
        //await handleDynamicRoutes(data.data.menuTree)
      } catch(error) {
        console.error('获取用户信息失败',error)
      }
    }
    //检查权限
    const hasPermission=(permission) => {
      return buttonList.value.includes(permission);
    }
    const init=async () => {
      const token=userToken.value

      if(token) {
        setUserToken(token);
        try {
          const menuData=await fetchMenuData();
          buttonList.value=menuData.data.perms||[]
          console.log(buttonList.value)
          await handleDynamicRoutes(menuData.data.menus);
        } catch(error) {
          console.error('初始化菜单失败',error);
        }
      }
    }

    return {userInfo,userToken,buttonList,getUserInfo,setUserInfo,setUserToken,login,logout,init}
  },
  {persist: true}
)
