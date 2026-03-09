<template>
  <view class="page-wrap">
    <view class="page-title">我的消息</view>
    <view v-for="item in list" :key="item.id" class="card list-card">
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.summary }}</view>
      <view class="muted" style="margin-top: 10rpx">{{ item.created_at }}</view>
    </view>
  </view>
</template>

<script>
import { fetchMessages } from '@/api/index.js';

export default {
  data() {
    return {
      list: []
    };
  },
  onLoad() {
    fetchMessages().then(data => {
      this.list = data;
    });
  }
};
</script>

<style>
.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 10rpx;
}
</style>
