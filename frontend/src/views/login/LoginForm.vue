<script setup>
import { UserOutlined, LockOutlined } from '@ant-design/icons-vue'
import { ref, computed } from 'vue'
import { Icon } from '@iconify/vue'
import { message } from 'ant-design-vue'
import { useLayoutThemeStore } from '@/store/layout/layoutTheme.js'
import { useUserStore } from '@/store/business/user.js'
import { login } from '@/api/login' // 确保这里正确引入了 login 函数

//定义加载动画
const loading = ref(false)
// 从环境变量中获取 API 基础路径
const apiBaseUrl = import.meta.env.VITE_APP_API_BASE_URL

// 创建一个响应式变量来存储验证码图片的完整 URL
const imageCodeUrl = ref(`${apiBaseUrl}api/login/verify?${new Date().getTime()}`)

// 更新验证码图片
const refreshImageCode = () => {
  // 更新验证码 URL，添加新的时间戳来避免缓存问题
  imageCodeUrl.value = `${apiBaseUrl}api/login/verify?${new Date().getTime()}`
}
const layoutSetting = useLayoutThemeStore().layoutSetting
const borderRadius = computed(() => layoutSetting.borderRadius)

const userStore = useUserStore()

const formState = ref({
  username: '',
  password: '',
  verifyCode: ''
})

const onFinish = async (values) => {
  loading.value = true; // 开始请求前，设置为加载状态
  try {
    // 调用 login 函数并等待它完成
    const loginResult = await login(
      formState.value.username,
      formState.value.password,
      formState.value.verifyCode
    );
    // 登录成功后的逻辑
    if (loginResult.code === 1) {
      message.success('登录成功！');
      userStore.login(loginResult.data); // 确保 userStore 已经正确定义并导入
      window.isRouteInited=true;
    } else {
      refreshImageCode();
      message.error(loginResult.msg || '登录失败，请重试！');
    }
  } catch (error) {
    // 登录失败的逻辑
    refreshImageCode();
    // 直接从 error 对象读取 msg 属性。如果 error 对象没有 msg 属性，则输出默认的错误消息。
    message.error(error.msg || '登录失败，请重试！');
  } finally {
    loading.value = false; // 请求完成后，无论成功或失败，都取消加载状态
  }
};


const onFinishFailed = (errorInfo) => {
  console.log('Failed:', errorInfo)
}
</script>

<template>
  <a-form :model="formState" @finish="onFinish" @finishFailed="onFinishFailed">
    <a-form-item name="username" :rules="[{ required: true, message: '请输入用户名！' }]" >
      <a-input v-model:value="formState.username" placeholder="用户名" style="height: 40px;">
        <template #prefix >
          <UserOutlined />
        </template>
      </a-input>
    </a-form-item>
    <a-form-item name="password" :rules="[{ required: true, message: '请输入密码！' }]">
      <a-input-password v-model:value="formState.password" placeholder="密码" style="height:40px;">
        <template #prefix>
          <LockOutlined />
        </template>
      </a-input-password>
    </a-form-item>
    <!-- 如需验证码请打开注释 -->
    <!--<a-form-item name="verifyCode" :rules="[{ required: true, message: '请输入验证码！' }]">
      <div class="verify-container">
        <a-input
          class="verify-input"
          v-model:value="formState.verifyCode"
          placeholder="右侧图片验证码"
        >
          <template #prefix>
            <Icon icon="material-symbols-light:key" />
          </template>
        </a-input>
        <img :src="imageCodeUrl" class="verify-image" @click="refreshImageCode" />
      </div>
    </a-form-item>-->
    <a-form-item>
      <a-checkbox v-model:checked="formState.rememberMe">记住密码</a-checkbox>
      <a style="float: right;" href="javascript:void(0);" @click="onForgotPassword">忘记密码?</a>
    </a-form-item>
    <a-form-item>
      <a-button
        class="w100%"
        type="primary"
        html-type="submit"
        :loading="loading"
        :disabled="loading"
        style="height:36px"
        >登录</a-button
      >
    </a-form-item>
  </a-form>
</template>

<style scoped lang="less">
/* 确保输入框和按钮填充适当的空间 */
.verify-container {
  display: flex; /* 使用flex布局使项目在一行显示 */
  align-items: center; /* 垂直居中对齐 */
}

.verify-input,
.ant-input-affix-wrapper {
  flex-grow: 1; /* 输入框填充可用空间 */
  margin-right: 8px; /* 输入框和图片之间的间隔 */
  min-height: 32px;
  height: 32px; /* 设置输入框的高度 */

  // 修正内部输入框的高度
  .ant-input {
    height: 100%;
  }
}

.verify-image {
  cursor: pointer;
  height: 32px; /* 验证码图片的高度与输入框一致 */
  border: 1px solid #d9d9d9;
  border-radius: 4px; /* 您可以在这里使用您的borderRadius变量 */
}

// 调整密码输入框的高度
.a-input-password .ant-input-affix-wrapper,
.a-input-password .ant-input {
  
  height: 40px;
}

// 如果您使用的是Ant Design的图标组件，可能还需要调整图标的大小和对齐方式
.anticon {
  line-height: 32px; /* 使图标垂直居中 */
}

.ant-input {
  background-color: aqua;
}
</style>
