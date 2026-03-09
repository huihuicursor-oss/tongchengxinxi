<template>
  <view class="page-wrap">
    <view class="page-title">我的会员</view>
    <view class="card">
      <view class="item-title">{{ info.current_plan }}</view>
      <view class="muted">到期时间：{{ info.expire_at }}</view>
      <view style="height: 20rpx"></view>
      <view v-for="(item, index) in info.rights || []" :key="index" class="right-row">
        <text class="dot"></text>
        <text>{{ item }}</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchVipInfo } from '@/api/index.js';

export default {
  data() {
    return {
      info: {}
    };
  },
  onLoad() {
    fetchVipInfo().then(data => {
      this.info = data;
    });
  }
};
</script>

<style>
.item-title {
  font-size: 32rpx;
  font-weight: 700;
  margin-bottom: 12rpx;
}

.right-row {
  display: flex;
  align-items: center;
  gap: 12rpx;
  margin-top: 14rpx;
}

.dot {
  width: 12rpx;
  height: 12rpx;
  border-radius: 50%;
  background: #f26628;
}
</style>
