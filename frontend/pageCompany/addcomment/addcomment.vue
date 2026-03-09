<template>
  <view class="page-wrap">
    <view class="page-title">发表点评</view>
    <view class="card">
      <input v-model="form.user" class="input" placeholder="称呼" />
      <view style="height: 16rpx"></view>
      <input v-model="form.score" class="input" placeholder="评分 1-5" />
      <view style="height: 16rpx"></view>
      <textarea v-model="form.content" class="textarea" placeholder="说说你的消费体验"></textarea>
      <view style="height: 20rpx"></view>
      <view class="primary-btn" @click="submit">提交点评</view>
    </view>
  </view>
</template>

<script>
import { createMerchantComment } from '@/api/index.js';

export default {
  data() {
    return {
      merchantId: 'm-1',
      form: {
        user: '',
        score: '5',
        content: ''
      }
    };
  },
  onLoad(options) {
    this.merchantId = options.id || 'm-1';
  },
  methods: {
    submit() {
      if (!this.form.content) {
        uni.showToast({ title: '请输入点评内容', icon: 'none' });
        return;
      }
      createMerchantComment({
        merchant_id: this.merchantId,
        ...this.form
      }).then(() => {
        uni.showToast({ title: '点评成功', icon: 'success' });
        setTimeout(() => {
          uni.redirectTo({ url: `/pageCompany/storecomment/storecomment?id=${this.merchantId}` });
        }, 700);
      });
    }
  }
};
</script>
