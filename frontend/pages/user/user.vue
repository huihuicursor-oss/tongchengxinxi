<template>
  <view class="page-wrap">
    <view class="muted">正在进入我的页面...</view>
  </view>
</template>

<script>
export default {
  onLoad() {
    uni.redirectTo({ url: '/pages/tab/user' });
  }
};
</script>
