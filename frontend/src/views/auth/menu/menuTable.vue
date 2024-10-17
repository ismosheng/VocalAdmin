<template>
  <a-table 
    :columns="columns" 
    :dataSource="dataSource" 
    childrenColumnName="allChildren"
    :loading="loading"
    :scroll="{ y: '450px', x: '1000px' }"
    >
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'icon'">
        <Icon class="iconify anticon" :icon="record.icon" />
      </template>
      <template v-if="column.key === 'type'">
        <a-tag v-if="record.type === 'M'" :bordered="false" color="processing">菜单</a-tag>
        <a-tag v-else :bordered="false" color="green">按钮</a-tag>
      </template>
      <template v-if="column.key === 'is_show'">
        <a-switch
          :checked="record.is_show === 1"
          :checkedChildren="'展示'"
          :unCheckedChildren="'隐藏'"
          @change="(checked) => handleShowChange(checked, record)"
        ></a-switch>
      </template>
      <template v-if="column.key === 'is_disable'">
        <a-switch
          :checked="record.is_disable === 1"
          :checkedChildren="'正常'"
          :unCheckedChildren="'禁用'"
          @change="(checked) => handleDisableChange(checked, record)"
        ></a-switch>
      </template>
      <template v-if="column.key === 'action'">
        <a @click="handleEdit(record)" v-permission="'menuEdit'">编辑</a>
        <a-divider type="vertical" />
        <a-popconfirm title="确定要删除该菜单吗？" @confirm="handleDelete(record)">
          <a href="#" v-permission="'menuDel'">删除</a>
        </a-popconfirm>
      </template>
    </template>
    <template #title>
      <div style="text-align: left">
        <a-button type="primary" @click="handleEdit()" style="margin-right: 8px;" v-permission="'menuAdd'">新增</a-button>
        <a-tooltip>
          <template #title>刷新</template>
          <ReloadOutlined @click="handleRefresh" style="cursor: pointer" />
        </a-tooltip>
      </div>
  </template>

  </a-table>
</template>
<script setup>
import { ref } from 'vue';
import { defineProps, defineEmits } from 'vue'
import { ReloadOutlined } from '@ant-design/icons-vue'
import { Icon } from '@iconify/vue'

const props = defineProps({
  dataSource: Array,
  loading: Boolean,
})
const emits = defineEmits(['edit', 'delete', 'refresh','save'])

const columns = [
  {
    title: '菜单名称',
    dataIndex: 'title',
  },
  {
    title: '图标',
    dataIndex: 'icon',
    key: 'icon',
    width: '12%',
  },
  {
    title: '类型',
    dataIndex: 'type',
    key: 'type',
    width: '12%',
  },
  {
    title: '路由',
    dataIndex: 'path',
    width: '20%',
  },
  {
    title: '排序',
    dataIndex: 'sort',
    width: '10%',
  },
  {
    title: '是否展示',
    dataIndex: 'is_show',
    width: '10%',
    key: 'is_show',
  },
  {
    title: '状态',
    dataIndex: 'is_disable',
    width: '10%',
    key: 'is_disable',
  },
  {
    title: '操作',
    key: 'action',
    fixed: 'right',
    width: 150
  }
];

const handleEdit = (record) => {
  emits('edit', record)
}

const handleDelete = (record) => {
  emits('delete', record)
}

const handleRefresh = () => {
  emits('refresh')
}

const handleDisableChange = (checked, record) => {
  // 将 checked 状态转换为对应的数值
  const newStatus = checked ? 1 : 0
  console.log('状态改变了')
  record.is_disable = newStatus
  emits('save', record,1)
}

const handleShowChange = (checked, record) => {
  // 将 checked 状态转换为对应的数值
  const newStatus = checked ? 1 : 0
  console.log('状态改变了')
  record.is_show = newStatus
  emits('save', record,1)
}
</script>