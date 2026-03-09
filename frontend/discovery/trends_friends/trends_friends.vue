<template>
  <view class="page-wrap">
    <view class="page-title">圈友</view>
    <view v-for="item in friends" :key="item.id" class="card list-card">
      <view class="item-title">{{ item.nickname }}</view>
      <view class="muted">{{ item.city }}</view>
      <view class="tag-row">
        <text v-for="tag in item.tags" :key="tag" class="tag-chip">{{ tag }}</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchTrendsFriends } from '@/api/index.js';

export default {
  data() {
    return {
      friends: []
    };
  },
  onLoad() {
    fetchTrendsFriends().then(data => {
      this.friends = data;
    });
  }
};
</script>

<style>
.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 8rpx;
}

.tag-row {
  display: flex;
  flex-wrap: wrap;
  gap: 12rpx;
  margin-top: 14rpx;
}

.tag-chip {
  padding: 8rpx 14rpx;
  background: #f4f6f8;
  border-radius: 999rpx;
  font-size: 22rpx;
}
</style>
