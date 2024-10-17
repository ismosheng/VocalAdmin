import {useUserStore} from '@/store/business/user';
import {watch} from 'vue';

export default {
    mounted(el,binding) {
        const userStore=useUserStore();
        const permission=binding.value; // 这里是 'add_user'

        const checkPermission=() => {
            // 检查 buttonList 是否包含当前的 permission
            if(Array.isArray(userStore.buttonList)&&!userStore.buttonList.includes(permission)) {
                // 如果没有权限，移除元素
                el.parentNode&&el.parentNode.removeChild(el);
            }
        };

        // 初始检查
        checkPermission();
        // 监听 buttonList 的变化
        watch(() => userStore.buttonList,checkPermission); // 监听 buttonList 的变化
    }
};
