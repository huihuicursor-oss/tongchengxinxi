<template>
  <view class="page-wrap">
    <view class="page-title">意见反馈</view>
    <textarea v-model="content" class="textarea" placeholder="请输入你对平台的建议"></textarea>
    <view style="height: 20rpx"></view>
    <view class="primary-btn" @click="submit">提交反馈</view>
  </view>
</template>

<script>
import { createFeedback } from '@/api/index.js';

export default {
  data() {
    return {
      content: ''
    };
  },
  methods: {
    submit() {
      if (!this.content) {
        uni.showToast({ title: '请输入反馈内容', icon: 'none' });
        return;
      }
      createFeedback({ content: this.content }).then(result => {
        uni.showToast({ title: `提交成功 ${result.ticket_no}`, icon: 'none' });
        this.content = '';
      });
    }
  }
};
</script>
