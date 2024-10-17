<template>
    <a-row gutter="24" style="margin-top: 12px">
        <a-col :span="24">
            <a-card >
                <menuTable
                :dataSource="data"
                :loading="loading"
                @save="handleSave"
                @edit="openEditModal"
                @delete="handleDelete"
                @refresh = "handleRefresh"
                />
            </a-card>
        </a-col>
    </a-row>
    
    <!-- 新增/编辑模态框 -->
    <menuModal
        :visible="isShow"
        :initialData="selectedData"
        :menuTree="data"
        @close="closeModal"
        @save="handleSave"
    />
</template>

<script setup>

import { h, ref, reactive, onMounted, computed } from 'vue'
import { message } from 'ant-design-vue';
import menuTable from './menuTable.vue'
import menuModal from './menuModal.vue'
import {fetchMenuAll , addMenu, editMenu, deleteMenu} from '@/api/auth/menu'

//是否展示模态
const isShow = ref(false)
const selectedData = ref(null)

const loading = ref(false)
const data = ref()
//页面加载的时候获取数据
onMounted(() => {
  fetchData() 
})

//获取数据
const fetchData = async () => {
    const param = {
        'getAll':1
    }
  loading.value = true
  try {
    const response = await fetchMenuAll(param)
    data.value = response.data
    if(data){
        addKey(data.value)
    }
    console.log(data.value);
  } catch (error) {
    console.error('获取信息失败:', error)
  } finally {
    loading.value = false
  }
}
const addKey = (data) => {
  data.forEach((item, index) => {
    item.key = item.id;
    if (item.allChildren && item.allChildren.length > 0) {
      addKey(item.allChildren);
    }else{
        delete item.allChildren;
    }
  });
}

//刷新
const handleRefresh = () => {
    fetchData() 
}

const openEditModal = (record) => {
    selectedData.value = record
    isShow.value = true
}

const closeModal = () => {
    isShow.value = false
    selectedData.value = null // 重置选中数据
}
const handleSave  = async (data,radioChange=0) => {
    let response = null;
    if(data.id){
        response = await editMenu(data)
    }else{
        response = await addMenu(data)
    }
    console.log(response.code)
    if(response.code == 1){
      message.success('保存成功')
      closeModal() // 关闭模态框
      fetchData() // 刷新数据
    }else{
      message.error(response.msg)
      if(radioChange == 1){
        closeModal() // 关闭模态框
        fetchData() // 刷新数据
      }

    }
}

//删除
const handleDelete = async (record) => {
  try {
    const response = await deleteMenu(record.id)
    if (response.code === 1) {
      message.success('删除成功')
      fetchData() // 刷新数据
    } else {
        message.error(response.msg)
    }
  } catch (error) {
    console.error('删除失败:', error)
  }
}
</script>

<style lang="scss" scoped>

</style>