<template>
  <view class="page-wrap">
    <view class="muted">正在进入商家详情...</view>
  </view>
</template>

<script>
export default {
  onLoad(options) {
    uni.redirectTo({ url: `/pages/merchant/detail?id=${options.id || 'm-1'}` });
  }
};
</script>
