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
        <a-col :span="12">
          <a-form-item label="菜单名称" name="title" :rules="[{ required: true, message: '请输入菜单名称' }]">
            <a-input v-model:value="form.title" placeholder="请输入菜单名称" />
          </a-form-item>
          <a-form-item label="父级菜单" name="pid" :rules="[{ required: true, message: '请选择父级菜单' }]">
            <a-tree-select
              v-model:value="form.pid"
              placeholder="请选择父级菜单，不选默认最高级"
              tree-default-expand-all
              :allowClear="true"
              :fieldNames="{children:'allChildren', label:'title', value: 'id' }"
              :show-search="true"
              :tree-data="wrappedMenuTree"
              :treeDefaultExpandedKeys="[0]"
            >
                
            </a-tree-select>
          </a-form-item>
          <a-form-item label="菜单类型" name="type" :rules="[{ required: true, message: '请选择菜单类型' }]">
            <a-select v-model:value="form.type" placeholder="请选择菜单类型">
              <a-select-option value="M">菜单</a-select-option>
              <a-select-option value="B">按钮</a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item label="排序" :rules="[{ required: true, message: '请输入排序' }]">
            <a-input
              type="number"
              v-model:value="form.sort"
              placeholder="请输入排序,数值越小排位越靠前"
            />
          </a-form-item>
          <a-form-item label="前端路由" :rules="[{ message: '请输入前端路由' }]">
            <a-input v-model:value="form.component" placeholder="请输入前端路由" />
          </a-form-item>
        </a-col>
        <!-- 右侧列 -->
        <a-col :span="12">
          <a-form-item label="唯一标识" name="name" :rules="[{ required: true, message: '请输入唯一标识' }]">
            <a-input v-model:value="form.name" placeholder="请输入唯一标识" />
          </a-form-item>
          <a-form-item label="权限标识" name="perms" :rules="[{ message: '请输入权限标识' }]">
            <a-input v-model:value="form.perms" placeholder="请输入权限标识" />
          </a-form-item>
          <a-form-item label="路由" :rules="[{ message: '请输入路由' }]">
            <a-input v-model:value="form.path" placeholder="请输入路由" />
          </a-form-item>
          <a-form-item label="图标" :rules="[{ message: '请输入图标' }]">
            <a-input v-model:value="form.icon" placeholder="请输入图标" />
            <a href='https://icon-sets.iconify.design/fontisto/nav-icon-list-a/' target='about'>点击搜索</a>
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="32">
        <a-col :span="24">
          <a-form-item label="是否展示" :rules="[{ required: true, message: '请选择是否展示' }]">
            <a-radio-group v-model:value="form.is_show">
              <a-radio :value="1">展示</a-radio>
              <a-radio :value="0">隐藏</a-radio>
            </a-radio-group>
          </a-form-item>
          <a-form-item label="是否禁用" :rules="[{ required: true, message: '请选择是否禁用' }]">
            <a-radio-group v-model:value="form.is_disable">
              <a-radio :value="1">正常</a-radio>
              <a-radio :value="0">禁用</a-radio>
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
  menuTree: Array // 传过来的部门数据
})

const emits = defineEmits(['close', 'save'])

const form = ref({
  pid: 0,
  component:'',
  type: 'M',
  name: '',
  title: '',
  icon:'',
  sort: 0,
  perms: '',
  path: '',
  is_show: 1,
  is_disable: 1
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
        component:'',
        type: 'M',
        name: '',
        title: '',
        icon:'',
        sort: 0,
        perms: '',
        path: '',
        is_show: 1,
        is_disable: 1
      }
    }
  }
)

const wrappedMenuTree = ref([])
watch(
  () => props.menuTree,
  (newData) => {
    if (newData && newData.length > 0) {
      // 包裹 menuTree 数据
      wrappedMenuTree.value = [{
        id: 0,
        title: '主菜单',
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
    component: form.value.component,
    pid: form.value.pid,
    type: form.value.type,
    name: form.value.name,
    icon:form.value.icon,
    title: form.value.title,
    sort: form.value.sort,
    perms: form.value.perms,
    path: form.value.path,
    is_show: form.value.is_show,
    is_disable: form.value.is_disable
  }
  emits('save', { ...formData }) // 将包含选中角色数据和部门 ID 的对象传递给父组件
}

const handleCancel = () => {
  emits('close') // Emit close event
  form.value = {
    pid: 0,
    component:'',
    type: 'M',
    name: '',
    title: '',
    icon:'',
    sort: 0,
    perms: '',
    path: '',
    is_show: 1,
    is_disable: 1
  }
}
</script>