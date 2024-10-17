// 引入配置好的axios实例
import request from '@/api/request'

//获取部门列表
function fetchDeptList(params) {
    const url= '/adminapi/system.AdminDept/getTree'
    return request
       .get(url, { params }) // 在GET请求中通过params对象传递查询参数
       .then((response) => {
            return response.data;
        })
       .catch((error) => {
            throw error;
        });
}

//新增 编辑
function addDept(data) {
    const url= '/adminapi/system.AdminDept/add'
    return request
    .post(url, data) // 在POST请求中通过data对象传递表单数据
    .then((response) => {
            return response.data;
        })
    .catch((error) => {
            throw error;
        });
}
function editDept(data) {
    const url= '/adminapi/system.AdminDept/edit'
    return request
    .post(url, data) // 在POST请求中通过data对象传递表单数据
    .then((response) => {
            return response.data;
        })
    .catch((error) => {
            throw error;
        });
}

//删除
function deleteDept(id) {
    const url= '/adminapi/system.AdminDept/delete'
    return request
 .post(url, { id }) // 在POST请求中通过data对象传递表单数据
 .then((response) => {
            return response.data;
        })
 .catch((error) => {
            throw error;
        });
}

export {
    fetchDeptList,
    addDept,editDept,
    deleteDept
}