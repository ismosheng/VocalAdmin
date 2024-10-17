/*
 * @Description: 
 * @Date: 2024-09-29 08:16:30
 * @LastEditTime: 2024-09-29 16:29:44
 * @FilePath: \dev1.1\src\router\dynamicRoutes.js
 */
import {useSystemStore} from '@/store/layout/system';
import {defineAsyncComponent} from 'vue';

const modules=import.meta.glob('@/views/**/*.vue')
const modulesKeysToImport={}
Object.keys(modules).forEach(key => {
    const nameMatch=key.match(/\/src\/views\/(.+)\.vue/)
    if(!nameMatch) return
    const indexMatch=nameMatch[1].match(/(.*)\/Index$/i)
    let name=indexMatch? indexMatch[1]:nameMatch[1];
    modulesKeysToImport[name]=modules[key]
})

// 动态导入组件的方法
function loadView(viewPath) {
    const realPath=viewPath.replace(/\.vue$/,'');
    const loadFn=modulesKeysToImport[realPath||viewPath];
    if(loadFn) {
        return defineAsyncComponent(loadFn);
    } else {
        console.warn(`Component ${viewPath} not found.`);
        return undefined;
    }
}


// 转换单个菜单项为路由对象
function menuItemToRoute(menuItem,parentNamePath=[]) {
    // 加载组件
    let routeComponent;
    if(menuItem.component) {
        routeComponent=loadView(menuItem.component);
    }

    // 创建当前路由的名称路径数组
    const currentNamePath=[...parentNamePath,menuItem.name];
    // 构建路由对象
    const route={
        path: menuItem.path,
        name: menuItem.name,
        component: routeComponent,
        meta: {
            title: menuItem.title,
            icon: menuItem.icon,
            namePath: currentNamePath,  // 将名称路径数组添加到 meta 数据中
        },
        
        // 如果是根路径且有子级，则将重定向设置为第一个子级的完整路径
        redirect: menuItem.allChildren&&menuItem.allChildren.length>0
            ? `${menuItem.allChildren[0].path}`
            :undefined
    };

    // 如果有子路由，则递归添加
    if(menuItem.allChildren&&menuItem.allChildren.length>0) {
        route.children=menuItem.allChildren.map(child => menuItemToRoute(child,currentNamePath));
    }

    return route;
}



// 转换整个菜单树为路由数组
function menuTreeToRoutes(menuTree) {
    const routes=[];
    menuTree.forEach(menuItem => {
        routes.push(menuItemToRoute(menuItem));
    });
    const systemStore=useSystemStore()
    systemStore.menus=routes
    return routes;
}

// 导出函数以便在其他模块中使用
export {menuTreeToRoutes};
