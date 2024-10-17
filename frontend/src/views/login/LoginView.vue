<script setup>
import { computed } from 'vue'
import { useLayoutThemeStore } from '@/store/layout/layoutTheme.js'
import LoginForm from '@/views/login/LoginForm.vue'
import { Icon } from '@iconify/vue'

const layoutSetting = useLayoutThemeStore().layoutSetting
const algorithm = computed(() => layoutSetting.algorithm)
const title = computed(() => layoutSetting.title)
const isDarkMode = computed(() => layoutSetting.algorithm === 'darkAlgorithm')
const toggleDarkMode = () => {
  layoutSetting.algorithm =
    layoutSetting.algorithm === 'darkAlgorithm' ? 'lightAlgorithm' : 'darkAlgorithm'
}

</script>

<template>
  <div class="login-container"  style="backgroundImage: url('/src/assets/images/bg.jpg')">
    <!-- 登录表单区域 -->
    <div class="login-main">
      <a-card class="login-card"  :style="cardStyle">
        <div class="flex-cc overflow-hidden whitespace-nowrap font-500 text-20px" :style="style">
          <img class="h32px mr10px" src="~@/assets/images/logo.png" alt="" />
          <div >
           <span class="login-title">{{ title }}</span>
          </div>
        </div>
        
        <span class="login-hint">请使用您的账户登录</span>
        <!-- 添加的灰色文本提示 -->
        <LoginForm />
      </a-card>
    </div>

    <!-- 夜间模式切换开关 -->
    <div class="theme-switcher">
      <a-switch @change="toggleDarkMode" :checked="isDarkMode">
        <template #checkedChildren><Icon icon="bitcoin-icons:moon-filled" /></template>
        <template #unCheckedChildren><Icon icon="icon-park-outline:sun" /></template>
      </a-switch>
    </div>
  </div>
</template>


<style scoped>

.theme-switcher {
  position: absolute;
  top: 20px;
  right: 20px;
  z-index: 1000;
  transform: scale(1.5);
  padding: 5px;
}

.login-container {
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden; /* 隐藏溢出的内容，确保视频被容纳 */
  display: flex; /* 使用flex布局 */
  justify-content: flex-end; /* 将内容对齐到右侧 */
  align-items: center; /* 垂直居中 */
   background-size: cover;
  background-position: center center;
}

.login-main {
  width: 400px;
  margin-right: 10%; /* 将登录卡片移动到右侧 */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 16px;
  z-index: 10; /* 确保表单在视频之上 */
  height: 500px; /* 调整卡片的高度 */
}

.login-card {
  width: 100%;
  padding: 20px;
  height: 100%; /* 使卡片填充整个.login-main容器 */

  background-color: rgba(255, 255, 255, 0.8);
  background-image: radial-gradient(circle at 93% 1e+02%, rgba(22, 119, 255, 0.17) 0%, rgba(255, 255, 255, 0.05) 23%, rgba(255, 255, 255, 0.03) 87%, rgba(22, 119, 255, 0.12) 109%);
}

.login-title {
  display: block;
  text-align: center; 
  font-weight: 600;
  font-size: 33px;
}

.login-hint {
  display: block; /* 使文本提示独占一行 */
  color: rgba(0, 0, 0, 0.65); /* 灰色文本 */
  text-align: center; /* 文本居中 */
  margin-top: 10px; /* 与标题的间距 */
  margin-bottom: 10px;
}


/* 响应式布局 */
@media (max-width: 768px) {
  .login-main {
    width: 90%;
    margin: 0 5%; /* 在小屏幕上占据更多空间并居中 */
  }
}

@media (max-width: 480px) {
  .theme-switcher {
    top: 5px;
    right: 5px;
  }
}

</style>
