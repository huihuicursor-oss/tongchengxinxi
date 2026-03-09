<template>
  <view class="page-wrap">
    <view class="muted">正在进入发布中心...</view>
  </view>
</template>

<script>
export default {
  onLoad() {
    uni.redirectTo({ url: '/pages/tab/publish' });
  }
};
</script>
