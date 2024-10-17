<template>
  <a-table
    size="500px"
    :columns="columns"
    :dataSource="dataSource"
    :loading="loading"
    :rowKey="record => record.key"
    :scroll="{ y: '450px', x: '1000px' }"
    :pagination="pagination"
  >
    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'action'">
        <a @click="handleEdit(record)">编辑权限</a>
        <a-divider type="vertical" />
        <a-popconfirm title="确定要删除角色吗？" @confirm="handleDelete(record)">
          <a href="#">删除</a>
        </a-popconfirm>
      </template>
    </template>
  </a-table>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  dataSource: Array,
  loading: Boolean,
  pagination: Object
});

const emits = defineEmits(['edit', 'delete', 'pageChange']);


const columns = [
  {
    title: '角色名称',
    dataIndex: 'name'
  },
  {
    title: '角色描述',
    dataIndex: 'desc'
  },
  {
    title: '创建时间',
    dataIndex: 'create_time'
  },
  {
    title: '操作',
    key: 'action',
    fixed: 'right',
    width:150
  }
]

const handleEdit = (record) => {
  emits('edit', record);
};

const handleDelete = (record) => {
  emits('delete', record);
};
</script>

<style scoped>
/* ...样式... */
</style>
