<template>
    <a-modal
      v-model:visible="visible"
      title="编辑"
      ok-text="确认"
    cancel-text="取消"
      @ok="handleOk"
      @cancel="handleCancel"
    >
      <a-form :model="formData" >
        <a-form-item label="名称" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
          <a-input v-model:value="formData.name" />
        </a-form-item>
        <a-form-item label="包装" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
          <a-input v-model:value="formData.spec" />
        </a-form-item>
        <a-form-item label="状态" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
          <a-input v-model:value="formData.status" />
        </a-form-item>
        <a-form-item label="生产日期" :label-col="{ span: 5 }" :wrapper-col="{ span: 16 }">
          <a-input v-model:value="formData.scdate" />
        </a-form-item>
      </a-form>
      
    </a-modal>
  </template>
  
  
  <script setup>
  import { ref, watch } from 'vue';
  
  const props = defineProps({
    record: {
      type: Object,
      default: () => ({})
    }
  });
  
  const visible = ref(false);
  const formData = ref({ ...props.record });
  
  watch(() => props.record, (newRecord) => {
    formData.value = { ...newRecord };
  });
  
  const emit = defineEmits(['update:record']);
  
  const handleOk = () => {
    emit('update:record', formData.value);
    visible.value = false;
  };
  
  const handleCancel = () => {
    visible.value = false;
  };
  
  const open = () => {
    visible.value = true;
  };
  </script>
  
<style scoped>
</style>
  