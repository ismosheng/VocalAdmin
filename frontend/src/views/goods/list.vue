<template>
  <a-row :gutter="24">
    <SearchForm
      :fields="fields"
      :initial-values="initialValues"
      :advanced-keys="advancedKeys"
      :show-export-button="true"
      :exporting="exporting"
      @search="handleSearch"
      @reset="handleReset"
      @export="handleExport"
    />
  </a-row>
  <a-row gutter="24" style="margin-top: 24px">
    <!-- 表格卡片 -->
    <a-col :span="24">
      <a-card>
        <a-button @click="selectAllCurrentPage">选择当前页所有数据</a-button>
        <a-table
          :columns="columns"
          :dataSource="filteredData"
          :loading="loading"
          :rowKey="(record) => record.key"
          :scroll="{ y: '450px' , x: '1000px'  }"
          :rowSelection="rowSelection"
        >
          <!-- 状态列的自定义渲染 -->
          <template #bodyCell="{ column, record }">
            <template v-if="column.key === 'status'">
              <a-tag :color="getStatusTagColor(record.status)">
                {{ getStatusLabel(record.status) }}
              </a-tag>
            </template>
            <template v-if="column.key === 'action'">
              <a @click="showEditDialog(record)">编辑</a>
              <a-divider type="vertical" />
              <a-popconfirm title="你确定要删除吗？" ok-text="确认" cancel-text="取消">
                <a href="#">删除</a>
              </a-popconfirm>
            </template>
          </template>
        </a-table>
      </a-card>
    </a-col>
  </a-row>
  <!-- Edit组件实例 -->
  <Edit :record="currentRecord" v-model:visible="editVisible" @update:record="handleRecordUpdate" />
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import locale from 'ant-design-vue/es/date-picker/locale/zh_CN'
import Edit from './components/edit.vue' // 确保路径正确
import SearchForm from '@/components/SearchForm.vue'

const fields = reactive([
  {
    key: 'djbh',
    label: '单据编号',
    component: 'a-input',
    placeholder: '模糊搜索'
  },
  {
    key: 'dwmch',
    label: '单位名称',
    component: 'a-input',
    placeholder: '模糊搜索'
  },
  {
    key: 'dateRange',
    label: '创建时间',
    component: 'a-range-picker',
    placeholder: ['开始日期', '结束日期'],
  }
])
const initialValues = reactive({
  djbh: '',
  dateRange: '',
  dwmch: ''
})

// 定义响应式对象存储当前搜索条件
const currentSearchFields = reactive({ ...initialValues })

const advancedKeys = reactive([])
// 假设的数据和搜索状态
const data = ref([
  { key: '1', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '2', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'disabled' },
  { key: '3', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'unknown' },
  { key: '4', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '5', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'disabled' },
  { key: '6', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '7', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '8', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '9', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'unknown' },
  { key: '10', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '11', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '12', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' },
  { key: '13', name: '测试', spec: '1/包 * 24 粒', scdate: '2024年9月7日', status: 'active' }
  // ...更多数据
])
const searchKeyword = ref('')
const loading = ref(false)
const selectedRowKeys = ref([])

const rowSelection = {
  selectedRowKeys: selectedRowKeys.value,
  onChange: (selectedKeys) => {
    selectedRowKeys.value = selectedKeys
  }
}

const selectAllCurrentPage = () => {
  selectedRowKeys.value = filteredData.value.map((record) => record.key)
}
// 处理搜索
const handleSearch = (value) => {
  searchKeyword.value = value
}

// 计算过滤后的数据
const filteredData = computed(() => {
  if (!searchKeyword.value) {
    return data.value
  }
  return data.value.filter((item) =>
    Object.values(item).some((fieldValue) =>
      fieldValue.toString().toLowerCase().includes(searchKeyword.value.toLowerCase())
    )
  )
})

const getStatusTagColor = (status) => {
  switch (status) {
    case 'active':
      return 'green'
    case 'disabled':
      return 'red'
    case 'unknown':
      return 'default'
    // ...其他状态
    default:
      return 'default'
  }
}

const getStatusLabel = (status) => {
  switch (status) {
    case 'active':
      return '激活'
    case 'disabled':
      return '禁用'
    case 'unknown':
      return '未知'
    // ...其他状态
    default:
      return '未知'
  }
}

// 切换高级搜索的显示状态
const advanced = ref(false) // 控制额外搜索字段的显示状态

const toggleAdvanced = () => {
  advanced.value = !advanced.value
}
// 表格列配置
const columns = [
  {
    title: '商品名称',
    dataIndex: 'name'
  },
  {
    title: '包装',
    dataIndex: 'spec'
  },
  {
    title: '状态',
    key: 'status',
    align: 'center'
  },
  {
    title: '出厂日期',
    dataIndex: 'scdate'
  },
  {
    title: '操作',
    key: 'action',
    align: 'center'
  }
]

const editVisible = ref(false)
const currentRecord = ref({})

const showEditDialog = (record) => {
  currentRecord.value = { ...record }
  editVisible.value = true
}

const handleRecordUpdate = (updatedRecord) => {
  // 处理记录更新逻辑，例如发送请求到服务器或更新本地数据
  // 例如，找到并更新data中的记录
  const index = data.value.findIndex((item) => item.key === updatedRecord.key)
  if (index !== -1) {
    data.value[index] = updatedRecord
  }
}
</script>

<style scoped></style>