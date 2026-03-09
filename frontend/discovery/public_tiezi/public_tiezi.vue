<template>
  <view class="page-wrap">
    <view class="page-title">发布帖子</view>
    <view class="card">
      <input v-model="form.title" class="input" placeholder="请输入标题" />
      <view style="height: 16rpx"></view>
      <input v-model="form.topic" class="input" placeholder="请输入话题，如：同城活动" />
      <view style="height: 16rpx"></view>
      <textarea v-model="form.content" class="textarea" placeholder="分享一下你的动态"></textarea>
      <view style="height: 20rpx"></view>
      <view class="primary-btn" @click="submit">发布帖子</view>
    </view>
  </view>
</template>

<script>
import { createDiscovery } from '@/api/index.js';

export default {
  data() {
    return {
      form: {
        title: '',
        topic: '',
        content: ''
      }
    };
  },
  methods: {
    submit() {
      if (!this.form.title || !this.form.content) {
        uni.showToast({ title: '请填写标题和内容', icon: 'none' });
        return;
      }
      createDiscovery(this.form).then(() => {
        uni.showToast({ title: '发帖成功', icon: 'success' });
        setTimeout(() => {
          uni.redirectTo({ url: '/pages/discovery/index' });
        }, 700);
      });
    }
  }
};
</script>
