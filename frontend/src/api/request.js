import axios from 'axios';
import qs from 'qs';
import {useUserStore} from '@/store/business/user'; // 假设这是你的用户状态管理库
import router from '@/router'; // 假设这是你的Vue路由实例

const request=axios.create({
  baseURL: import.meta.env.DEV? '/api':import.meta.env.VITE_APP_API_BASE_URL,
});

// 请求拦截器
request.interceptors.request.use(
  (config) => {
    // 添加请求参数序列化
    config.paramsSerializer=params => qs.stringify(params,{arrayFormat: 'repeat'});

    // 自动添加token到请求头（如果存在）
    const userStore=useUserStore();

    if(userStore.userToken) { // 确保使用 userToken.value 访问 token
      config.headers['Authorization']=`Bearer ${userStore.userToken}`;
    }

    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// 响应拦截器
request.interceptors.response.use(
  (response) => {
    // 直接返回响应数据
    return response;
  },
  (error) => {
    // 检查错误响应状态码，如果是401或403，则重定向到登录页面
    if(error.response&&(error.response.status===401||error.response.status===403)) {
      const userStore=useUserStore();
      userStore.logout(); // 清除用户状态
      router.push('/login').catch(err => console.error('Router redirect error:',err));
    }
    return Promise.reject(error);
  }
);

// 导出配置后的axios实例
export default request;
