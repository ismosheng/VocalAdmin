<template>
  <a-row gutter="24" >
    <a-col :span="24">
      <a-card>
        <div class="table-operations">
          <a-button @click="showDrawer()"  type="primary">新增</a-button>
          
        </div>
        <roleTable
          :dataSource="data"
          :loading="loading"
          :pagination="pagination"
          @edit="showDrawer"
          @delete="handleDelete"
          @pageChange="handlePageChange"
        />
      </a-card>
    </a-col>
  </a-row>
  <roleDrawer
    :open="open"
    :currentRole="currentRole"
    :menuTreeData="menuTreeData"
    :menus = "menus"
    @update:open="open = $event"
    @submit="handleRoleSubmit"
  />
</template>

<script setup>
import { h, ref, reactive, onMounted, computed } from 'vue'
import locale from 'ant-design-vue/es/date-picker/locale/zh_CN'
//引入接口
import { CarryOutOutlined } from '@ant-design/icons-vue';
import { fetchRoleGetList, addRole,editRole, fetchRoleDelete } from '@/api/auth/role'
import { fetchMenuAll } from '@/api/auth/menu'
import SearchForm from '@/components/SearchForm.vue'
import roleDrawer from './roleDrawer.vue'
import roleTable from './roleTable.vue'

import { message } from 'ant-design-vue'

const data = ref()
const loading = ref(false)

//搜索组件调用的
const fields = reactive([
  {
    key: 'name',
    label: '角色名称',
    component: 'a-input',
    placeholder: '模糊搜索'
  },
  {
    key: 'desc',
    label: '角色名称',
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
  fetchData() // 传入当前激活的标签页的 key
  loadMenuData()
})
const fetchData = async (param) => {
  loading.value = true
  try {
    const params = {
      ...currentSearchFields, // 使用当前搜索条件
      page: pagination.page,
      limit: pagination.limit
    }
    const response = await fetchRoleGetList(params)
    data.value = response.data.data
    pagination.total = response.data.total
  } catch (error) {
    console.error('获取库存信息失败:', error)
  } finally {
    loading.value = false
  }
}
//搜索
const handleSearch = (searchFields) => {
  Object.assign(currentSearchFields, searchFields) // 更新当前搜索条件
  pagination.page = 1 // 重置到第一页
  fetchData() // 使用更新后的搜索条件重新获取数据
}
//重置搜索条件
const handleReset = () => {
  Object.keys(initialValues).forEach((key) => {
    currentSearchFields[key] = initialValues[key]
  })
  pagination.page = 1 // 重置到第一页
  fetchData() // 使用重置后的搜索条件重新获取数据
}

const handlePageSizeChange = (current, size) => {
  pagination.page = 1 // 通常用户希望回到第一页
  pagination.limit = size
  fetchData() // 重新获取数据
}

const handlePageChange = (page, pageSize) => {
  pagination.page = page
  pagination.limit = pageSize
  fetchData() // 重新获取数据
}

//编辑
const open = ref(false)
//定义响应数据
const currentRole = reactive({
  id: null,
  name: '',
  desc: '',
  menuIds: []
})
const menus = ref([])
// 加载所有权限菜单
const loadMenuData = async () => {
  try {
    const response = await fetchMenuAll({ getAll: 1 })
    if (response.code === 1) {
      menus.value = response.data
    }
  } catch (error) {
    console.error('加载菜单数据失败', error)
  }
}



const menuTreeData = ref([])
const showDrawer = (record) => {
  open.value = true;
  if (record) {
    // 编辑操作
    currentRole.id = record.id;
    currentRole.name = record.name;
    currentRole.desc = record.desc;

    // 检查 record.menu_ids 是否为字符串，如果是，则解析为数组
    let menuIds = record.menu_ids;
    if (typeof record.menu_ids === 'string') {
      menuIds = JSON.parse(record.menu_ids);
    }
    menuTreeData.value = transformToTreeData(menuIds);

    currentRole.menuIds = menuIds; // 直接使用解析后的 menuIds
  } else {
    // 新增操作
    currentRole.id = null;
    currentRole.name = '';
    currentRole.desc = '';
    currentRole.menuIds = [];
    menuTreeData.value = transformToTreeData([]); // 传入空数组以重置树形数据
  }
};
// 数据转换函数：将扁平结构转换为树形结构
function transformToTreeData(flatData) {
  const data = JSON.parse(JSON.stringify(flatData)) // 深拷贝，避免修改原始数据
  const result = []
  const map = {}

  // 首先，将所有项存储到 map 对象中，以 id 为键
  data.forEach((item) => {
    map[item.id] = { ...item, children: [] }
  })

  // 再次遍历数据，根据 pid 将子节点添加到对应的父节点中
  data.forEach((item) => {
    const parent = map[item.pid]
    if (parent) {
      parent.children.push(map[item.id])
    } else {
      // 如果没有找到对应的父节点，那么它是顶级节点
      result.push(map[item.id])
    }
  })

  // 最后，根据需要调整每个节点的结构以适应 a-tree 组件
  const transformNode = (node) => ({
    title: node.title,
    key: node.id,
    icon: node.icon, // 如果你的树形控件需要图标，可以保留这一行
    // 其他你需要保留的字段...
    children: node.children.length ? node.children.map((child) => transformNode(child)) : undefined
  })

  return result.map(transformNode)
}
const onClose = () => {
  open.value = false
}
const handleRoleSubmit = async (data) => {
  // 从 data 中提取 menuIds
  const { menuIds } = data;
  // 创建一个 Set 来存储所有唯一的 ID
  const uniqueIds = new Set();

  // 遍历 menuIds，分割每个 key 并将结果加入到 Set 中
  menuIds.forEach(key => {
    key.split('-').forEach(part => {
      uniqueIds.add(parseInt(part)); // 将字符串转换为整数并添加到 Set
    });
  });

  // 将 Set 转换为数组并排序
  const sortedIds = Array.from(uniqueIds).sort((a, b) => a - b);
  
  // 准备提交的数据，包括处理过的 menuIds
  const submitData = {
    ...data,
    menu_ids: sortedIds
  };

  try {
    let response;
    if (currentRole.id) {
      // 如果 currentRole.id 存在，执行编辑操作
      response = await editRole({ ...submitData, id: currentRole.id });
      message.success('角色编辑成功');
    } else {
      // 否则，执行添加操作
      response = await addRole(submitData);
      message.success('角色添加成功');
    }
    // 成功后的一些操作，例如关闭抽屉、刷新列表等
    open.value = false;
    fetchData();
  } catch (error) {
    // 错误处理
    message.error(`操作失败: ${error.message}`);
  }
};


</script>

<style scoped>
.table-operations {
  margin-bottom: 16px;
}

.table-operations > button {
  margin-right: 8px;
}
</style>