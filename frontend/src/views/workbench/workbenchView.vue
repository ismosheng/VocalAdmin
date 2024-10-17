<template>
  <a-row :gutter="24">
    <a-col :sm="24" :md="12" :xl="6" :style="{ marginBottom: '24px', width: '100%' }">
      <chart-card :loading="loading" title="总销售额" total="￥126,560">
        <template #action>
          <a-tooltip title="指标说明">
            <InfoCircleOutlined />
          </a-tooltip>
        </template>
        <div>
          <trend flag="up" style="margin-right: 16px">
            <template #term>周同比</template>
            12%
          </trend>
          <trend flag="down">
            <template #term>日同比</template>
            11%
          </trend>
        </div>
        <template #footer> 日均销售额<span>￥ 234.56</span> </template>
      </chart-card>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6" :style="{ marginBottom: '24px', width: '100%' }">
      <chart-card :loading="loading" :title="title2" total="126,560">
        <template #action>
          <a-tooltip title="指标说明">
            <InfoCircleOutlined />
          </a-tooltip>
        </template>
        <div>
          <!-- 内容 -->
        </div>
        <template #footer> 日访问量<span>666</span> </template>
      </chart-card>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6" :style="{ marginBottom: '24px', width: '100%' }">
      <chart-card :loading="loading" title="访问量" total="126,560">
        <template #action>
          <a-tooltip title="指标说明">
            <InfoCircleOutlined />
          </a-tooltip>
        </template>
        <div>
          <!-- 统计图表 -->
        </div>
        <template #footer> 日访问量<span>666</span> </template>
      </chart-card>
    </a-col>
    <a-col :sm="24" :md="12" :xl="6" :style="{ marginBottom: '24px', width: '100%' }">
      <chart-card :loading="loading" title="总订单数" total="126,560">
        <template #action>
          <a-tooltip title="指标说明">
            <InfoCircleOutlined />
          </a-tooltip>
        </template>
        <div>
          <!-- 内容 -->
        </div>
        <template #footer> 日访问量<span>666</span> </template>
      </chart-card>
    </a-col>
  </a-row>
</template>

<script setup>
import { ref } from 'vue'
import ChartCard from '@/components/common/Charts/ChartCard.vue'
import Trend from '@/components/common/Trend/Trend.vue'
import { InfoCircleOutlined } from '@ant-design/icons-vue'

const loading = ref(false)
</script>
