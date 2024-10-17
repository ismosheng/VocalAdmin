<template>
  <a-card :loading="loading" :body-style="{ padding: '20px 24px 8px' }" :bordered="false">
    <div class="chart-card-header">
      <div class="meta">
        <span class="chart-card-title">
          <slot name="title">
            {{ title }}
          </slot>
        </span>
        <span class="chart-card-action">
          <slot name="action"></slot>
        </span>
      </div>
      <div class="total">
        <slot name="total">
          <span>{{ typeof total === 'function' && total() || total }}</span>
        </slot>
      </div>
    </div>
    <div class="chart-card-content">
      <div class="content-fix">
        <slot></slot>
      </div>
    </div>
    <div class="chart-card-footer">
      <div class="field">
        <slot name="footer"></slot>
      </div>
    </div>
  </a-card>
</template>
<script setup>
import { ref, computed,onMounted } from 'vue'

// Props
const props = defineProps({
  loading: Boolean,
  title: String,
  total: [String, Function]
})

// 内部状态
const internalCount = ref(0)

// 计算属性
const computedTotal = computed(() => {
  // 如果 total 是函数，则调用它；否则直接使用 total
  return typeof props.total === 'function' ? props.total() : props.total
})

// 生命周期钩子，例如 onMounted
onMounted(() => {
  console.log('ChartCard mounted')
})
</script>

<style scoped>
  .chart-card-header {
      position: relative;
      overflow: hidden;
      width: 100%;
    }

.chart-card-header.meta {
  position: relative;
  overflow: hidden;
  width: 100%;
  color: rgba(0, 0, 0, 0.45);
  font-size: 14px;
  line-height: 22px;
}

.chart-card-action {
  cursor: pointer;
  position: absolute;
  top: 0;
  right: 0;
}

.chart-card-footer {
  border-top: 1px solid #e8e8e8;
  padding-top: 9px;
  margin-top: 8px;
}

.chart-card-footer > * {
  position: relative;
}

.chart-card-footer.field {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
}

.chart-card-content {
  margin-bottom: 12px;
  position: relative;
  height: 46px;
  width: 100%;
}

.chart-card-content.content-fix {
  position: absolute;
  left: 0;
  bottom: 0;
  width: 100%;
}

.total {
  overflow: hidden;
  text-overflow: ellipsis;
  word-break: break-all;
  white-space: nowrap;
  color: #000;
  margin-top: 4px;
  margin-bottom: 0;
  font-size: 30px;
  line-height: 38px;
  height: 38px;
}
</style>