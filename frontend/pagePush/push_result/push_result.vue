<template>
  <view class="page-wrap">
    <view class="card" style="text-align: center">
      <view class="page-title">发布成功</view>
      <view class="muted">你的信息已经提交，演示环境默认直接发布成功。</view>
      <view style="height: 24rpx"></view>
      <view class="primary-btn" @click="goPublish">继续发布</view>
    </view>
  </view>
</template>

<script>
export default {
  methods: {
    goPublish() {
      uni.reLaunch({ url: '/pages/tab/publish' });
    }
  }
};
</script>
