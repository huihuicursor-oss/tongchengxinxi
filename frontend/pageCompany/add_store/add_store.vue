<template>
  <view class="page-wrap">
    <view class="page-title">申请入驻</view>
    <view class="card">
      <input v-model="form.store_name" class="input" placeholder="店铺名称" />
      <view style="height: 16rpx"></view>
      <input v-model="form.mobile" class="input" placeholder="联系电话" />
      <view style="height: 16rpx"></view>
      <input v-model="form.category" class="input" placeholder="店铺分类" />
      <view style="height: 16rpx"></view>
      <textarea v-model="form.summary" class="textarea" placeholder="店铺简介"></textarea>
      <view style="height: 20rpx"></view>
      <view class="primary-btn" @click="submit">提交入驻申请</view>
    </view>
  </view>
</template>

<script>
import { createMerchantApply } from '@/api/index.js';

export default {
  data() {
    return {
      form: {
        store_name: '',
        mobile: '',
        category: '',
        summary: ''
      }
    };
  },
  methods: {
    submit() {
      if (!this.form.store_name || !this.form.mobile) {
        uni.showToast({ title: '请填写店铺名称和联系电话', icon: 'none' });
        return;
      }
      createMerchantApply(this.form).then(result => {
        uni.showToast({ title: `申请已提交 ${result.application_no}`, icon: 'none' });
      });
    }
  }
};
</script>
