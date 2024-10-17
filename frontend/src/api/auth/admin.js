// 引入配置好的axios实例
import request from '@/api/request'
//获取列表
function fetchAdminList(params){
    const url= '/adminapi/system.admin/getList'
    return request
        .get(url,{params}) // 在GET请求中通过params对象传递查询参数
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            throw error;
        });
}

// 新增
function addAdmin(data){
    const url= '/adminapi/system.admin/add'
    return request
     .post(url,data) // 在POST请求中通过data对象传递表单数据
     .then((response) => {
            return response.data;
        })
     .catch((error) => {
            throw error;
        });
}
//编辑
function editAdmin(data) {
    const url='/adminapi/system.admin/edit'
    return request
        .post(url,data) // 在POST请求中通过data对象传递表单数据
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            throw error;
        });
}

// 删除
function deleteAdmin(id){
    const url= '/adminapi/system.admin/delete'
    return request
 .post(url,{id}) // 在POST请求中通过data对象传递表单数据
 .then((response) => {
            return response.data;
        })
 .catch((error) => {
            throw error;
        });
}


export{
    fetchAdminList,
    addAdmin,
    editAdmin,
    deleteAdmin
}