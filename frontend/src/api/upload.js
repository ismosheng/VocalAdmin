
import request from '@/api/request';

// data 是 FormData 对象，其中包含了文件信息
function uploadFile(data) {
    return request({
        url: '/adminapi/common/upload', // 你的后端接口路径
        method: 'post',
        data: data, // 直接传入 FormData 对象
        headers: {
            'Content-Type': 'multipart/form-data', // 确保设置正确的 Content-Type
        },
    })
}

export {
    uploadFile
}
