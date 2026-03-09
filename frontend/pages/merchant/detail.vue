<template>
  <view class="page-wrap">
    <view class="card">
      <image class="cover" :src="detail.cover" mode="aspectFill"></image>
      <view class="page-title">{{ detail.name }}</view>
      <view class="muted">{{ detail.category_name }} · {{ detail.score }} 分</view>
      <view class="muted">{{ detail.summary }}</view>
      <view class="muted" style="margin-top: 12rpx">地址：{{ detail.address }}</view>
      <view class="muted">电话：{{ detail.phone }}</view>
    </view>

    <view class="card" style="margin-top: 24rpx">
      <view class="item-title">用户评价</view>
      <view v-for="(comment, index) in detail.comments || []" :key="index" class="comment-row">
        <view class="comment-user">{{ comment.user }} · {{ comment.score }} 分</view>
        <view class="muted">{{ comment.content }}</view>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchMerchantDetail } from '@/api/index.js';

export default {
  data() {
    return {
      detail: {}
    };
  },
  onLoad(options) {
    fetchMerchantDetail(options.id).then(data => {
      this.detail = data || {};
    });
  }
};
</script>

<style>
.cover {
  width: 100%;
  height: 320rpx;
  border-radius: 20rpx;
  margin-bottom: 20rpx;
}

.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 16rpx;
}

.comment-row {
  padding: 16rpx 0;
  border-bottom: 1rpx solid #f0f0f0;
}

.comment-user {
  font-weight: 600;
  margin-bottom: 8rpx;
}
</style>
