<template>
  <view class="page-wrap">
    <view class="muted">正在进入头条页...</view>
  </view>
</template>

<script>
export default {
  onLoad() {
    uni.redirectTo({ url: '/pages/tab/news' });
  }
};
</script>
