<template>
 <a-row :gutter="24">
      <SearchForm
        :fields="fields"
        :initial-values="initialValues"
        :advanced-keys="advancedKeys"
        :show-export-button="false"
        :exporting="exporting"
        @search="handleSearch"
        @reset="handleReset"
        @export="handleExport"
      />
    </a-row>
  <a-row gutter="24" style="margin-top: 12px">
    <a-col :span="24">
      <a-card >
        <adminTable
          :dataSource="data"
          :loading="loading"
          :pagination="pagination"
          @edit="openEditModal"
          @delete="handleDelete"
          @pageChange="handlePageChange"
          @refresh = "handleRefresh"
        />
      </a-card>
    </a-col>
  </a-row>
  <!-- 新增/编辑模态框 -->
  <adminModal
    :visible="isModalVisible"
    :initialData="selectedUser"
    :departments="departments"
    :roles="roles"
    @close="closeModal"
    @save="handleSave"
  />
</template>

<script setup>

import { h, ref, reactive, onMounted, computed } from 'vue'
import SearchForm from '@/components/SearchForm.vue'
import adminTable from './adminTable.vue'
import adminModal from './adminModal.vue'
import { message } from 'ant-design-vue'
import {fetchAdminList,editAdmin,addAdmin,deleteAdmin} from '@/api/auth/admin'
import {fetchDeptList} from '@/api/auth/dept'
import {fetchRoleGetList} from '@/api/auth/role'

//是否展示模态
const isModalVisible = ref(false)
const selectedUser = ref(null)
//定义部门数据
const departments = ref([])
const roles = ref([])
const data = ref()
const loading = ref(false)

//搜索组件调用的
const fields = reactive([
  {
    key: 'account',
    label: '账户',
    component: 'a-input',
    placeholder: '模糊搜索'
  },
  {
    key: 'name',
    label: '用户名',
    component: 'a-input',
    placeholder: '模糊搜索'
  }
])

const initialValues = reactive({
  name: '',
  desc: ''
})

const advancedKeys = reactive([])
// 定义响应式对象存储当前搜索条件
const currentSearchFields = reactive({ ...initialValues })

//导出必备
const exporting = ref(false) // 控制导出按钮的加载状态
const handleExport = async () => {}

const pagination = reactive({
  page: 1,
  limit: 10,
  total: 0
})
//页面加载的时候获取数据
onMounted(() => {
  fetchData() 
  fetchDeptListData()
  fetchRoleListData()
})
//获取部门数据
const fetchDeptListData = async () => {
  try {
    const response = await fetchDeptList()
    departments.value = response.data
  } catch (error) {
    console.error('获取部门列表失败:', error)
  }
}
//获取角色信息
const fetchRoleListData = async () => {
  try {
    const params = {
      page: 1,
      limit: 999
    }
    const response = await fetchRoleGetList(params)

    roles.value = response.data.data

  } catch (error) {
    console.error('获取角色列表失败:', error)
  }
}
//获取数据
const fetchData = async (param) => {
  loading.value = true
  try {
    const params = {
      ...currentSearchFields, // 使用当前搜索条件
      page: pagination.page,
      limit: pagination.limit
    }
    const response = await fetchAdminList(params)
    data.value = response.data.data
    pagination.total = response.data.total
  } catch (error) {
    console.error('获取信息失败:', error)
  } finally {
    loading.value = false
  }
}
//刷新
const handleRefresh = () => {
    fetchData() 
}

const openEditModal = (record) => {
  selectedUser.value = record
  isModalVisible.value = true
}

const closeModal = () => {
  isModalVisible.value = false
  selectedUser.value = null // 重置选中的用户
}

const handleSave  = async (data) => {
  let ok = false
  if (selectedUser.value) {
    const response = await editAdmin(data)
    if(response.code=== 1){
      ok = true
      message.success('编辑成功')
    }else{
      message.error(response.msg)
    }
  } else {
    // 新增用户逻辑
    const response = await addAdmin(data)
    if(response.code=== 1){
      ok = true
      message.success('新增成功')
    }else{
      message.error(response.msg)
    }
    console.log(response.data)
  }
  if(ok){
    closeModal() // 关闭模态框
    fetchData() // 刷新数据
  }
}

//删除
const handleDelete = async (record) => {
  try {
    const response = await deleteAdmin(record.id)
    if (response.code === 1) {
      message.success('删除成功')
      fetchData() // 刷新数据
    } else {
      message.error('删除失败')
    }
  } catch (error) {
    console.error('删除失败:', error)
  }
}

const handlePageChange = (page) => {
 
  pagination.page = page.current
  pagination.limit = page.pageSize

  fetchData() // 刷新数据
}

//搜索
const handleSearch = (searchFields) => {
    Object.assign(currentSearchFields, searchFields) // 更新当前搜索条件
    pagination.page = 1 // 重置到第一页
    fetchData() // 使用更新后的搜索条件重新获取数据
}
//重置搜索
 const handleReset = () => {
    Object.keys(initialValues).forEach((key) => {
      currentSearchFields[key] = initialValues[key]
    })
    pagination.page = 1 // 重置到第一页
    fetchData() // 使用重置后的搜索条件重新获取数据
  }

</script>
<style scoped>
</style>