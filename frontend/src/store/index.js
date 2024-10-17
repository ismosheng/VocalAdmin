import { createPinia } from 'pinia'
import { createPersistedState } from 'pinia-plugin-persistedstate'
import {useUserStore} from './business/user'; // 确保路径是正确的

const pinia = createPinia()

// 持久化插件
pinia.use(createPersistedState())

export const setupStore = (app) => {
  const pinia=createPinia();

  // 持久化插件
  pinia.use(createPersistedState());

  app.use(pinia);

  // 在这里初始化 user store
  const userStore=useUserStore();
  console.log('index.js')
  userStore.init();
}
