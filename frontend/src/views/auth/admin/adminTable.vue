<template>
  <a-table
    size="500px"
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :rowKey="(record) => record.key"
    :scroll="{ y: '450px', x: '1000px' }"
    :pagination="{
      current: pagination.page,
      pageSize: pagination.limit,
      total: pagination.total,
      onChange: handlePageChange,
      onShowSizeChange: handlePageSizeChange,
      showSizeChanger: true
    }"
    @change="handleTableChange"
  >
    <template #bodyCell="{ column, record }">
      <!-- 头像 -->
      <template v-if="column.key === 'avatar'">
        <a-avatar :size="40" :src="record.avatar" />
      </template>
      <!-- 用户名 -->
      <template v-if="column.key === 'name'">
        <a>{{ record.name }}</a>
      </template>
      <template v-if="column.key === 'disable'">
        <a-switch
          :checked="record.disable === 1"
          :checkedChildren="'正常'"
          :unCheckedChildren="'禁用'"
          @change="(checked) => handleStatusChange(checked, record)"
          size="small"
        ></a-switch>
      </template>

      <template v-if="column.key === 'dept'">
        <a-tag :bordered="false" color="processing">{{ record.dept.name }}</a-tag>
      </template>
      <template v-if="column.key === 'action'">
        <a @click="handleEdit(record)" v-permission="'adminEdit'">编辑</a>
        <a-divider type="vertical" />
        <a-popconfirm title="确定要删除该用户吗？" @confirm="handleDelete(record)">
          <a href="#" v-permission="'adminDel'">删除</a>
        </a-popconfirm>
      </template>
    </template>
    <template #title>
      <div style="text-align: right">
        <a-button type="primary" @click="handleEdit()" v-permission="'adminAdd'"  style="margin-right: 8px;">新增</a-button>
        <a-tooltip>
          <template #title>刷新</template>
          <ReloadOutlined @click="handleRefresh" style="cursor: pointer" />
        </a-tooltip>
      </div>
    </template>
  </a-table>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import { ReloadOutlined } from '@ant-design/icons-vue'
import { editAdmin } from '@/api/auth/admin'
import { message } from 'ant-design-vue'
const props = defineProps({
  dataSource: Array,
  loading: Boolean,
  pagination: Object
})

const emits = defineEmits(['edit', 'delete', 'pageChange', 'refresh'])

const columns = [
  {
    title: '头像',
    dataIndex: 'avatar',
    key: 'avatar',
    width: 120
  },
  {
    title: '账户',
    dataIndex: 'account'
  },
  {
    title: '用户名',
    dataIndex: 'name',
    key: 'name'
  },
  {
    title: '所属部门',
    dataIndex: 'dept',
    key: 'dept'
  },
  {
    title: '账户状态',
    dataIndex: 'disable',
    key: 'disable'
  },
  {
    title: '创建时间',
    dataIndex: 'create_time'
  },

  {
    title: '操作',
    key: 'action',
    fixed: 'right',
    width: 150
  }
]

const handleEdit = (record) => {
  emits('edit', record)
}

const handleDelete = (record) => {
  emits('delete', record)
}

const handleRefresh = () => {
  emits('refresh')
}

const handleTableChange = (pagination) => {
  // 触发 pageChange 事件，并传递新的分页信息
  emits('pageChange', pagination)
}
const handleStatusChange = (checked, record) => {
  const newStatus = checked ? 1 : 0
  record.status = newStatus
  const data = {
    id: record.id,
    account: record.account,
    name: record.name,
    disable: newStatus
  }

  editAdmin(data).then((res) => {
    if (res.code === 1) {
      message.success('状态修改成功')
      emits('refresh')
    } else {
      message.error(res.msg);
    }
  })
}
</script>
