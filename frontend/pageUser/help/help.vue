<template>
  <view class="page-wrap">
    <view class="page-title">帮助中心</view>
    <view v-for="item in list" :key="item.id" class="card list-card">
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.content }}</view>
    </view>
  </view>
</template>

<script>
import { fetchHelpArticles } from '@/api/index.js';

export default {
  data() {
    return {
      list: []
    };
  },
  onLoad() {
    fetchHelpArticles().then(data => {
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
