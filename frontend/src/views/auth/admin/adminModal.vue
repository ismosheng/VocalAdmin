<template>
  <a-modal
    :visible="visible"
    :title="isEdit ? '编辑用户' : '新增用户'"
    @cancel="handleCancel"
    @ok="handleOk"
  >
    <a-form ref="formRef" :model="form" layout="vertical">
      <a-form-item label="账户" name="account" :rules="[{ required: true, message: '请输入账户' }]">
        <a-input v-model:value="form.account" placeholder="请输入账户" />
      </a-form-item>
      <a-form-item label="昵称" name="name" :rules="[{ required: true, message: '请输入用户名' }]">
        <a-input v-model:value="form.name" placeholder="请输入用户名" />
      </a-form-item>

      <a-form-item
        label="密码(新增时必填)"
        name="password"
        :rules="[{ required: !isEdit, message: '请输入密码' }]"
      >
        <a-input-password v-model:value="form.password" />
      </a-form-item>
      <a-form-item
        label="所属部门"
        name="dept"
        :rules="[{ required: true, message: '请选择部门' }]"
      >
        <a-tree-select
          v-model:value="form.dept"
          :tree-data="treeDepartments"
          placeholder="请选择部门"
          tree-default-expand-all
          :show-search="true"
        />
      </a-form-item>
      <a-form-item label="角色" name="roles" :rules="[{ required: true, message: '请选择角色' }]">
        <a-select
  v-model:value="selectedRoles"
  mode="multiple"
  placeholder="请选择角色"
  @change="handleRoleChange"
>
  <a-select-option
    v-for="role in roles"
    :key="role.id"
    :value="role.id"
  >
    {{ role.name }}
  </a-select-option>
</a-select>

      </a-form-item>

      <a-form-item label="用户头像">
        <a-upload
          v-model:file-list="fileList"
          name="avatar"
          list-type="picture-card"
          class="avatar-uploader"
          :show-upload-list="false"
          :customRequest="handleUpload"
        >
          <img v-if="imageUrl" :src="imageUrl" alt="avatar" class="avatar" />
          <div v-else>
            <LoadingOutlined v-if="loading" />
            <PlusOutlined v-else />
            <div class="ant-upload-text">上传</div>
          </div>
        </a-upload>
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import { defineProps, defineEmits } from 'vue'
import { LoadingOutlined, PlusOutlined } from '@ant-design/icons-vue'
import { uploadFile } from '@/api/upload'
import { message } from 'ant-design-vue'

const props = defineProps({
  visible: Boolean,
  initialData: Object,
  departments: Array, // 传过来的部门数据
  roles: Array // 传过来的角色数据
})

const emits = defineEmits(['close', 'save'])

const form = ref({
  account: '',
  name: '',
  dept: null,
  create_time: '',
  avatar: '',
  password: '',
  roles: [] // 用于存储选中的角色，
})

const isEdit = ref(false)
const fileList = ref([])
const imageUrl = ref('')
const loading = ref(false)
const treeDepartments = ref([])
const selectedRoles = ref([])

watch(
  () => props.initialData,
  (newData) => {
    if (newData) {
      isEdit.value = true;
      form.value = {
        ...newData,
      };
      imageUrl.value = newData.avatar; // 使用原有头像 URL
      fileList.value = newData.avatar ? [{
        uid: '-1', // 可以是任意唯一标识
        name: 'avatar', // 这里的 name 不重要，只是为了显示
        status: 'done', // 表示已上传的状态
        url: newData.avatar // 设置为实际的头像 URL
      }] : [];
      form.value.dept = newData.dept?.id || null; // 确保 dept 是 ID
      selectedRoles.value = newData.roles.map(role => role.id);
    } else {
      isEdit.value = false;
      form.value = {
        account: '',
        name: '',
        dept: null,
        create_time: '',
        avatar: '', // 在这里保持为空，因为这是新增用户的情况
        password: '',
        roles: []
      };
      selectedRoles.value = []; // 确保没有选中的角色
    }
  },
  { immediate: true }
);

const buildTree = (data) => {
  const tree = []
  const map = {}

  // 将每个部门存入 map 中
  data.forEach((item) => {
    // 复制原始数据，并将 allChildren 赋值给 children
    map[item.id] = {
      title: item.name, // 显示名称
      value: item.id, // 作为值
      children: [] // 初始化 children
    }
  })

  // 构建树形结构
  data.forEach((item) => {
    const parent = map[item.pid]
    if (parent) {
      // 如果有父部门，将当前部门添加到父部门的 children 中
      parent.children.push(map[item.id])
    } else {
      // 如果没有父部门，说明是根节点，添加到树中
      tree.push(map[item.id])
    }

    // 将 allChildren 中的子部门添加到当前部门的 children 中
    if (item.allChildren && item.allChildren.length > 0) {
      item.allChildren.forEach((child) => {
        map[item.id].children.push({
          title: child.name,
          value: child.id,
          children: [] // 初始化子部门的 children
        })
      })
    }
  })

  return tree
}

// 监听 a-select 组件的 change 事件，更新选中的角色数据
const handleRoleChange = (value) => {
  selectedRoles.value = value
  form.value.roles = value // 确保 form 对象的 roles 也被更新
}

// Watch for the departments prop to update the tree structure
watch(
  () => props.departments,
  (newDepartments) => {
    // 如果需要构建树形结构，可以在这里调用 buildTree
    treeDepartments.value = buildTree(newDepartments)
  },
  { immediate: true }
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
    account: form.value.account,
    name: form.value.name,
    dept_id: form.value.dept, // 确保这里是部门的 ID
    avatar: form.value.avatar,
    ...(form.value.password && { password: form.value.password }),
    roles: selectedRoles.value // 包含选中的角色数据
  }

  emits('save', { ...formData }) // 将包含选中角色数据和部门 ID 的对象传递给父组件
}

const handleCancel = () => {
  emits('close') // Emit close event
  form.value = {
    account: '',
    name: '',
    dept: null,
    create_time: '',
    avatar: '',
    dept: null,
    password: '',
    roles: [] // 初始化为空数组
  }
}

const handleUpload = async ({ file, onSuccess, onError }) => {
  const formData = new FormData()
  formData.append('file', file) // 根据您的后端接口要求可能需要更改字段名

  try {
    const response = await uploadFile(formData)
    if (response.data.code === 1) {
      // 上传成功，将文件的路径添加到 fileList
      const fileObj = {
        uid: file.uid, // Ant Design Vue 需要的唯一标识
        name: file.name, // 文件名
        status: 'done', // 上传状态
        url: response.data.data[0] // 从后端返回的路径
      }
      fileList.value.push(fileObj) // 更新 fileList
      imageUrl.value = response.data.data[0] // 更新 imageUrl
      form.value.avatar = response.data.data[0] // 更新 form 的 avatar 字段
      onSuccess(response.data) // 通知组件上传成功
      message.success(`${file.name} 文件上传成功。`)
    } else {
      throw new Error(response.data.msg || '上传失败') // 抛出错误以触发 onError
    }
  } catch (error) {
    onError(error)
    message.error(`${file.name} 文件上传失败。${error.message || ''}`)
  }
}
</script>
<style scoped>
.avatar-uploader .ant-upload {
  width: 128px; /* 或你期望的宽度 */
  height: 128px; /* 或你期望的高度 */
}

.avatar {
  max-width: 100%;
  max-height: 100%;
  object-fit: cover; /* 这将确保图片按比例缩放，并填充整个容器 */
}
</style>
