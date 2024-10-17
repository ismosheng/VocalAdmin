// 引入配置好的axios实例
import request from '@/api/request'

function fetchMenuAll(params){
    const url= '/adminapi/system.Menu/getMenuTree'
    return request
        .get(url,{params}) // 在GET请求中通过params对象传递查询参数
        .then((response) => {
            return response.data;
        })
        .catch((error) => {
            throw error;
        });
}

function addMenu(data){
    const url= '/adminapi/system.menu/add'
    return request
     .post(url,data) // 在POST请求中通过data对象传递表单数据
     .then((response) => {
            return response.data;
        })
     .catch((error) => {
            throw error;
        });
}
function editMenu(data){
    const url= '/adminapi/system.menu/edit'
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
function deleteMenu(id){
    const url= '/adminapi/system.menu/delete'
    return request
 .post(url,{id}) // 在POST请求中通过data对象传递表单数据
 .then((response) => {
            return response.data;
        })
 .catch((error) => {
            throw error;
        });
}

export {
    fetchMenuAll,addMenu,editMenu,deleteMenu
}