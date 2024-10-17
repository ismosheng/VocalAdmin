/*
 * @Description: 
 * @Date: 2024-09-29 08:16:30
 * @LastEditTime: 2024-09-29 16:30:41
 * @FilePath: \dev1.1\src\api\login.js
 */
// 引入配置好的axios实例
import request from '@/api/request'; // 确保路径正确

// 登录函数
function login(username, password,verifyCode) {
  // 定义登录接口的URL
  const url = '/adminapi/login/login';
  // 准备登录请求的body参数
  const data = {
    username,
    password,
    verifyCode,
  };
  // 发送POST请求到登录接口
  return request.post(url, data)
    .then(response => {
      // 可以在这里处理登录成功后的逻辑，例如保存token
      return response.data;
    })
    .catch(error => {
      // 请求失败，处理错误
      console.error(error);
      throw error;
    });
}

// 导出登录函数，以便在其他地方调用
export { login };
