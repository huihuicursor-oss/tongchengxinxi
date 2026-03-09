<template>
  <view class="page-wrap">
    <view class="page-title">全部评价</view>
    <view v-for="(item, index) in comments" :key="index" class="card list-card">
      <view class="item-title">{{ item.user }} · {{ item.score }} 分</view>
      <view class="muted">{{ item.content }}</view>
    </view>
  </view>
</template>

<script>
import { fetchMerchantComments } from '@/api/index.js';

export default {
  data() {
    return {
      comments: []
    };
  },
  onLoad(options) {
    fetchMerchantComments(options.id || 'm-1').then(data => {
      this.comments = data;
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
