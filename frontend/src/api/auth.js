
// 引入配置好的axios实例
import request from '@/api/request'


function fetchMenuData() {
    // 定义登录接口的URL
    const url= '/adminapi/system.Menu/getMenuTree'
    return request
        .post(url)
        .then((response) => {
            return response.data
        })
        .catch((error) => {
            // 请求失败，处理错误
            console.error(error)
            throw error
        })
   
}

export
{
    fetchMenuData
}