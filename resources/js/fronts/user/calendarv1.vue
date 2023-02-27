<template>
  <a-modal v-model:visible="visible" :title="modalText1" :confirm-loading="confirmLoading" @ok="handleOk(modalText1, content, type)">
    <p>{{ modalText1 }}</p>
    <p>{{ modalText2 }}</p>
    <select name="memo[type]" :value="type">
      <option value="error" selected>error</option>
      <option value="warning">warning</option>
      <option value="success">success</option>
    </select>
    <input type="text" name="memo[content]" :value="content" placeholder="new">
  </a-modal>
  <a-calendar v-model:value="value" @select="showModal(value)">
    <template #dateCellRender="{ current }">
      <ul class="events" :class="current.format('YYYY-MM-DD').toString()">
        <li v-for="item in data[current.format('YYYY-MM-DD').toString()]" :key="item.content">
          <a-badge :status="item.type" :text="item.content" />
        </li>
      </ul>
    </template>
    <template #monthCellRender="{ current }">
      <div v-if="getMonthData(current)" class="notes-month">
        <section>{{ getMonthData(current) }}</section>
        <span>Backlog number</span>
      </div>
    </template>
  </a-calendar>
</template>
<script>
import { defineComponent, ref } from 'vue';
export default defineComponent({
  setup() {
    const data = ref({
      '2023-02-13': [
        { type: 'error', content: 'test00' },
        { type: 'warning', content: 'test01' },
        { type: 'success', content: 'test02' },
      ]
    });
    const getMonthData = value => {
      if (value.month() === 8) {
        return 1394;
      }
    };
    const modalText1 = ref('Content of the modal');
    const modalText2 = ref('Content of the modal');
    const visible = ref(false);
    const confirmLoading = ref(false);
    const showModal = (value) => {
      const date = value.format('YYYY-MM-DD').toString();
      modalText1.value = date;
      visible.value = true;
    };
    const handleOk = (date, content, type) => {
      modalText2.value = "The modal will be closed after two seconds";
      confirmLoading.value = true;
      console.log(date, content, type)
      setTimeout(() => {
        if (
          content !== '' && content !== undefined && 
          type !== '' && type !== undefined && 
          date !== '' && date !== undefined
        ) {
            data.date = [...data.date, {'type': type, 'content': content}]
          }
        visible.value = false;
        confirmLoading.value = false;
      }, 2000);
    };
    let content = ref()
    let type = ref()
    return {
      data,
      getMonthData,
      showModal,
      handleOk,
      visible,
      confirmLoading,
      modalText1,
      modalText2,
      content,
      type
    };
  },
});
</script>
<style scoped>
.events {
  list-style: none;
  margin: 0;
  padding: 0;
}
.events .ant-badge-status {
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
  text-overflow: ellipsis;
  font-size: 12px;
}
.notes-month {
  text-align: center;
  font-size: 28px;
}
.notes-month section {
  font-size: 28px;
}
</style>