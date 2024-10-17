<template>
  <a-modal
    :visible="visible"
    :title="isEdit ? '编辑菜单' : '新增菜单'"
    @cancel="handleCancel"
    @ok="handleOk"
  >
    <a-form ref="formRef" :model="form" layout="vertical">
      <a-row :gutter="32">
        <!-- 左侧列 -->
        <a-col :span="24">
          <a-form-item label="部门名称" name="name" :rules="[{ required: true, message: '请输入部门名称' }]">
            <a-input v-model:value="form.name" placeholder="请输入部门名称" />
          </a-form-item>
          <a-form-item label="上级部门" name="pid" :rules="[{ required: true, message: '请选择上级部门' }]">
            <a-tree-select
              v-model:value="form.pid"
              placeholder="请选择上级部门"
              tree-default-expand-all
              :allowClear="true"
              :fieldNames="{children:'allChildren', label:'name', value: 'id' }"
              :show-search="true"
              :tree-data="wrappedMenuTree"
            >
                
            </a-tree-select>
          </a-form-item>
          <a-form-item label="负责人" :rules="[{ message: '请输入负责人' }]">
            <a-input v-model:value="form.leader" placeholder="请输入负责人" />
          </a-form-item>
          <a-form-item label="手机号" :rules="[{ message: '请输入手机号' }]">
            <a-input
              type="number"
              v-model:value="form.mobile"
              placeholder="请输入手机号"
            />
          </a-form-item>
          <a-form-item label="排序" :rules="[{ message: '请输入排序' }]">
            <a-input
              type="number"
              v-model:value="form.sort"
              placeholder="请输入排序,数值越小排位越靠前"
            />
          </a-form-item>
          <a-form-item label="状态" :rules="[{ required: true, message: '请选择状态' }]">
            <a-radio-group v-model:value="form.status">
              <a-radio :value="1">正常</a-radio>
              <a-radio :value="0">停用</a-radio>
            </a-radio-group>
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
  </a-modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import { defineProps, defineEmits } from 'vue'
const props = defineProps({
  visible: Boolean,
  initialData: Object,
  treeData: Array // 传过来的部门数据
})

const emits = defineEmits(['close', 'save'])

const form = ref({
  pid: 0,
  name: '',
  leader: '',
  mobile:'',
  sort: 0,
  status: 1,
})

const isEdit = ref(false)
watch(
  () => props.initialData,
  (newData) => {
    if (newData) {
      isEdit.value = true
      form.value = {
        ...newData
      }
    } else {
      isEdit.value = false
      form.value = {
        pid: 0,
        name: '',
        leader: '',
        mobile:'',
        sort: 0,
        status: 1,
      }
    }
  }
)

const wrappedMenuTree = ref([])
watch(
  () => props.treeData,
  (newData) => {
    if (newData && newData.length > 0) {
      // 包裹 menuTree 数据
      wrappedMenuTree.value = [{
        id: 0,
        name: '顶级部门',
        allChildren: newData
      }];
    } else {
      wrappedMenuTree.value = [];
    }
  },
  { immediate: true, deep: true } // 确保立即和深度监听
)

const formRef = ref(null) // 引用表单
const handleOk = async () => {
  // 检查是否有新上传的头像
  const isValid = await formRef.value.validate()
  if (!isValid) {
    return // 校验不通过，直接返回
  }
  // 构建要传递的数据对象，包括选中的角色数据和部门 ID
  const formData = {
    id: form.value.id,
    pid: form.value.pid,
    name: form.value.name,
    leader: form.value.leader,
    mobile: form.value.mobile,
    sort: form.value.sort,
    status: form.value.status,
  }
  emits('save', { ...formData }) // 将包含选中角色数据和部门 ID 的对象传递给父组件
}

const handleCancel = () => {
  emits('close') // Emit close event
  form.value = {
    pid: 0,
    name: '',
    leader: '',
    mobile:'',
    sort: 0,
    status: 1,
  }
}
</script>