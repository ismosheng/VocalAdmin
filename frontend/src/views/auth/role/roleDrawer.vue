<template>
  <a-drawer :width="400" title="编辑权限" placement="right" :open="open" @close="onClose">
    <a-form :model="currentRole" ref="formRef" :rules="rules">
      <a-form-item
        label="角色名称"
        name="name"
        :rules="[{ required: true, message: '请输入角色名称' }]"
      >
        <a-input v-model:value="currentRole.name" placeholder="请输入角色名称"></a-input>
      </a-form-item>
      <a-form-item
        label="角色描述"
        name="desc"
        :rules="[{ required: true, message: '请输入角色描述' }]"
      >
        <a-input v-model:value="currentRole.desc" placeholder="请输入角色描述"></a-input>
      </a-form-item>
      <a-form-item label="排序">
        <a-input v-model:value="currentRole.sort" placeholder="排序"></a-input>
      </a-form-item>
    </a-form>
    <a-tree
      :tree-data="localMenuTreeData"
      :checked-keys="checkedKeys"
      checkable
      selectable="{false}"
      @check="onCheck"
      :defaultExpandAll="true"
      :showLine="true"
    ></a-tree>
    <template #extra>
      <a-button type="primary" @click="submitRole">保存</a-button>
    </template>
  </a-drawer>
</template>

<script setup>
import { ref, watch, watchEffect } from 'vue'
import { fetchMenuAll } from '@/api/auth/menu'

// Props
const props = defineProps({
  open: Boolean,
  currentRole: Object,
  menuTreeData: Array,
  menus: Array
})

// Emits
const emit = defineEmits(['update:open', 'submit'])

// 定义响应式数据
const checkedKeys = ref([])
const localMenuTreeData = ref([])

const transformMenuData = (menuData, parentKey = '') => {
  return menuData.map((item, index) => {
    // 构建当前节点的 key 值。如果有 parentKey，则在前面加上 parentKey 和一个分隔符（如 '-'）。
    const key = parentKey ? `${parentKey}-${item.id}` : `${item.id}`
    return {
      title: item.title,
      key: key,
      // 如果存在子节点，递归调用 transformMenuData 并传入当前 key 作为 parentKey。
      children: item.allChildren ? transformMenuData(item.allChildren, key) : []
    }
  })
}
//转换层级
const buildHierarchicalKeys = (menuData, menuIds) => {
  let hierarchicalKeys = [];
  const traverse = (nodes, path = '') => {
    for (const node of nodes) {
      // 构建当前节点的路径。如果已有路径，则在前面加上父路径和'-'，否则直接使用当前节点的key
      const currentPath = path ? `${path}-${node.key.split('-').pop()}` : node.key;
      const itemId = parseInt(node.key.split('-').pop());
      if (menuIds.includes(itemId)) {
        if (!node.children || node.children.length === 0) {
          // 只有叶子节点的ID才被添加到勾选列表中
          hierarchicalKeys.push(currentPath);
        }
      }
      if (node.children && node.children.length > 0) {
        traverse(node.children, currentPath);
      }
    }
  };
  traverse(menuData);
  return hierarchicalKeys;
};


// 当currentRole变化时，更新checkedKeys
watch(
  () => props.open,
  (newOpen) => {
    if (newOpen) {
      // 确保 menus 数据已经被转换并且 currentRole.menuIds 是可用的
      localMenuTreeData.value = transformMenuData(props.menus)
      if (props.currentRole && props.currentRole.menuIds) {
        // 编辑操作：基于 currentRole.menuIds 设置 checkedKeys
        checkedKeys.value = buildHierarchicalKeys(
          localMenuTreeData.value,
          props.currentRole.menuIds
        )
        console.log(checkedKeys.value,props.currentRole.menuIds)

      } else {
        // 新增操作：重置checkedKeys为空数组
        checkedKeys.value = []
      }
    }
  },
  { immediate: true }
)
// 关闭抽屉
const onClose = () => {
  emit('update:open', false)
}

// 添加校验规则
const rules = {
  name: [{ required: true, message: '请输入角色名称', trigger: 'blur' }],
  desc: [{ required: true, message: '请输入角色描述', trigger: 'blur' }]
}
// 添加表单引用
const formRef = ref(null)

// 提交角色信息
const submitRole = async ()=> {
  const isValid = await formRef.value.validate();
  if (!isValid) {
    return; // 校验不通过，直接返回
  }
  emit('submit', { ...props.currentRole, menuIds: checkedKeys.value })
}

// 处理树形控件的check事件
const onCheck = (checkedKeysValue, e) => {
  checkedKeys.value = checkedKeysValue
}
</script>