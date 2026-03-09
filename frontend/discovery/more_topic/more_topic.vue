<template>
  <view class="page-wrap">
    <view class="page-title">更多话题</view>
    <view v-for="item in topics" :key="item.id" class="card list-card">
      <view class="item-title"># {{ item.name }}</view>
      <view class="muted">{{ item.posts }} 个帖子正在讨论</view>
    </view>
  </view>
</template>

<script>
import { fetchTopics } from '@/api/index.js';

export default {
  data() {
    return {
      topics: []
    };
  },
  onLoad() {
    fetchTopics().then(data => {
      this.topics = data;
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
