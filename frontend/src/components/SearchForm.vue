<template>
  <a-col :span="24">
    <a-card>
      <a-form>
        <!-- 基础搜索字段 -->
        <a-row :gutter="24">
          <a-col :md="8" :sm="24" v-for="field in baseFields" :key="field.key">
            <a-form-item :label="field.label">
              <component
                :is="field.component"
                v-model:value="searchFields[field.key]"
                :placeholder="field.placeholder"
                style="width: 100%"
              >
                <a-select-option
                  v-for="option in field.options"
                  :key="option.value"
                  :value="option.value"
                  v-if="field.component === 'a-select'"
                >
                  {{ option.label }}
                </a-select-option>
              </component>
            </a-form-item>
          </a-col>
        </a-row>
        <!-- 高级搜索字段 -->
        <a-row :gutter="24" v-if="advanced">
          <a-col :md="8" :sm="24" v-for="field in advancedFields" :key="field.key">
            <a-form-item :label="field.label">
              <component
                :is="field.component"
                v-model:value="searchFields[field.key]"
                :placeholder="field.placeholder"
                style="width: 100%"
              >
               <a-select-option
                  v-for="option in field.options"
                  :key="option.value"
                  :value="option.value"
                  v-if="field.component === 'a-select'"
                >
                  {{ option.label }}
                </a-select-option>
              </component>
            </a-form-item>
          </a-col>
        </a-row>
        <!-- 搜索操作 -->
        <a-row type="flex" justify="end" :gutter="24">
          <a-col md="24" sm="24">
            <div class="search-actions" style="text-align: right">
              <a-button type="primary" @click="emitSearch">搜索</a-button>
              <a-button @click="emitReset" style="margin-left: 8px">重置</a-button>
              <a-button v-if="showExportButton" @click="emitExport" type="default" style="margin-left: 8px" :loading="props.exporting">导出</a-button>

              <a-button type="link" @click="toggleAdvanced" class="advanced-toggle" v-if="showAdvancedToggle">
                {{ advanced ? '收起' : '展开' }}
                <UpOutlined v-if="!showAdvancedToggle" />
                <DownOutlined v-else />
              </a-button>
            </div>
          </a-col>
        </a-row>
      </a-form>
    </a-card>
  </a-col>
</template>

<script setup>
import { ref, reactive, watch, toRefs, computed, defineEmits } from 'vue'
import { DownOutlined, UpOutlined } from '@ant-design/icons-vue';

// 定义可以触发的事件
const emit = defineEmits(['search', 'reset', 'export'])

const props = defineProps({
  fields: {
    type: Array,
    default: () => []
  },
  initialValues: {
    type: Object,
    default: () => ({})
  },
  advancedKeys: {
    type: Array,
    default: () => []
  },
  showExportButton: {
    type: Boolean,
    default: false
  },
  exporting: Boolean // 接收父组件传递的导出状态
})


const { fields, initialValues, advancedKeys } = toRefs(props)

const searchFields = reactive({ ...initialValues.value })
const advanced = ref(false)

const baseFields = computed(() => fields.value.filter((f) => !advancedKeys.value.includes(f.key)))
const advancedFields = computed(() => fields.value.filter((f) => advancedKeys.value.includes(f.key)))

const showAdvancedToggle = computed(() => advancedKeys.value.length > 0);


watch(
  () => props.initialValues,
  (newVal) => {
    for (const key in newVal) {
      searchFields[key] = newVal[key]
    }
  },
  { deep: true }
)

const toggleAdvanced = () => {
  advanced.value = !advanced.value
}

const emitSearch = () => {
  // 这里进行日期格式转换
  const searchFieldsWithFormattedDates = { ...searchFields };
  if (searchFieldsWithFormattedDates.dateRange && searchFieldsWithFormattedDates.dateRange.length === 2) {
    const [start, end] = searchFieldsWithFormattedDates.dateRange;
    searchFieldsWithFormattedDates.dateRange = [
      start.toISOString().substring(0, 10), // 转换为 YYYY-MM-DD 格式
      end.toISOString().substring(0, 10)    // 转换为 YYYY-MM-DD 格式
    ];
  }
  emit('search', searchFieldsWithFormattedDates);
}
const emitReset = () => {
  for (const key in props.initialValues) {
    searchFields[key] = props.initialValues[key]
  }
  emit('reset')
}
const emitExport = () => {
  props.exporting = true; // 这里设置为true没有实际效果，因为exporting是从父组件传入的prop
  emit('export'); // 仅触发事件
};
</script>
