<template>
  <a-table 
    :columns="columns" 
    :dataSource="dataSource" 
    childrenColumnName="allChildren"
    :loading="loading"
    :scroll="{ y: '450px', x: '1000px' }"
    >
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'status'">
        <a-switch
          :checked="record.status === 1"
          :checkedChildren="'正常'"
          :unCheckedChildren="'停用'"
          @change="(checked) => handleStatusChange(checked, record)"
        ></a-switch>
      </template>
      <template v-if="column.key === 'action'">
        <a @click="handleEdit(record)" v-permission="'deptEdit'">编辑</a>
        <a-divider type="vertical" />
        <a-popconfirm title="确定要删除该部门吗？" @confirm="handleDelete(record)">
          <a href="#" v-permission="'deptDel'">删除</a>
        </a-popconfirm>
      </template>
    </template>
    <template #title>
      <div style="text-align: left">
        <a-button type="primary" @click="handleEdit()" style="margin-right: 8px;"v-permission="'deptAdd'">新增</a-button>
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
    title: '部门名称',
    dataIndex: 'name',
    width: '15%',
  },
  {
    title: '负责人',
    dataIndex: 'leader',
  },
  {
    title: '手机号',
    dataIndex: 'mobile',
    width: '15%',
  },
  {
    title: '状态',
    dataIndex: 'status',
    key: 'status'
  },
  {
    title: '排序',
    dataIndex: 'sort',
  },
  {
    title: '创建时间',
    dataIndex: 'create_time',
  },
  {
    title: '更新时间',
    dataIndex: 'update_time',
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

const handleStatusChange = (checked, record) => {
  // 将 checked 状态转换为对应的数值
  const newStatus = checked ? 1 : 0
  console.log('状态改变了')
  record.status = newStatus
  emits('save', record,1)
}
</script>