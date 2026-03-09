<template>
  <view class="page-wrap">
    <view class="page-title">圈子</view>
    <view
      v-for="item in list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-head">
        <text class="item-title">{{ item.title }}</text>
        <text class="topic">{{ item.topic }}</text>
      </view>
      <view class="muted">{{ item.content }}</view>
      <view class="meta-row">
        <text>{{ item.author }}</text>
        <text>{{ item.likes }} 赞</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchDiscoveryFeed } from '@/api/index.js';

export default {
  data() {
    return { list: [] };
  },
  onLoad() {
    fetchDiscoveryFeed().then(data => {
      this.list = data;
    });
  },
  methods: {
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=discovery` });
    }
  }
};
</script>

<style>
.item-head,
.meta-row {
  display: flex;
  justify-content: space-between;
  gap: 16rpx;
}

.item-title {
  font-size: 30rpx;
  font-weight: 700;
}

.topic {
  color: #845ef7;
}

.meta-row {
  margin-top: 12rpx;
  color: #8a8a8a;
  font-size: 24rpx;
}
</style>
