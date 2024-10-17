// 引入配置好的axios实例
import request from '@/api/request'

//查询列表
function fetchRoleGetList(params) {
    const url='/adminapi/system.AdminRole/getList'
    return request
        .get(url,{params}) // 在GET请求中通过params对象传递查询参数
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            throw error;
        });
}

//新增
function addRole(data) {
    const url='/adminapi/system.AdminRole/add'
    return request
        .post(url,data)
        .then((response) => {
            return response.data
        })
        .catch((error) => {
            throw error
        })
}

function editRole(data) {
    const url='/adminapi/system.AdminRole/edit'
    return request
        .post(url,data)
        .then((response) => {
            return response.data
        })
        .catch((error) => {
            throw error
        })
}

//删除
function fetchRoleDelete(data){
    const url= '/adminapi/system.AdminRole/delete'
    return request
        .post(url,data)
        .then((response) => {
            return response.data
        })
        .catch((error) => {
            throw error
        })
}
export {
    fetchRoleGetList,
    addRole,
    editRole,
    fetchRoleDelete
}