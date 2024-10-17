<template>
  <div class="trend chart-trend">
    <span v-if="isUp" class="trend-icon up">
         <slot name="term" class="text"></slot>
        <CaretUpOutlined style="color: #f5222d;"/>
    </span>
    
    <span v-else class="trend-icon down">
        <slot name="term" class="text"></slot>
        <CaretDownOutlined style="color: #52c41a;"/>
    </span>
    <slot></slot>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { CaretUpOutlined, CaretDownOutlined } from '@ant-design/icons-vue';

const props = defineProps({
  flag: {
    type: String,
    required: true,
    validator: (value) => ['up', 'down'].includes(value)
  }
});

const isUp = computed(() => props.flag === 'up');
</script>

<style scoped>
.chart-trend {
  display: inline-block;
  font-size: 14px;
  line-height: 22px;
}

.chart-trend.trend-icon {
  font-size: 20px;
  margin-right: 5px;
}

.chart-trend.trend-icon.up,
.chart-trend.trend-icon.down {
  margin-left: 4px;
  position: relative;
  top: 1px;
}

.chart-trend.trend-icon.up svg,
.chart-trend.trend-icon.down svg {
  font-size: 12px;
  transform: scale(1.67);
}
</style>
