<template>
  <view class="page-wrap">
    <view class="page-title">帖子</view>
    <view
      v-for="item in list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.content }}</view>
      <view class="meta-row">
        <text>{{ item.author }}</text>
        <text>{{ item.comments_count }} 评论</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchDiscoveryFeed } from '@/api/index.js';

export default {
  data() {
    return {
      list: []
    };
  },
  onLoad() {
    fetchDiscoveryFeed().then(data => {
      this.list = data;
    });
  },
  methods: {
    openDetail(id) {
      uni.navigateTo({ url: `/discovery/trends_detail/trends_detail?id=${id}` });
    }
  }
};
</script>

<style>
.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 10rpx;
}

.meta-row {
  display: flex;
  justify-content: space-between;
  margin-top: 12rpx;
  color: #8a8a8a;
  font-size: 24rpx;
}
</style>
