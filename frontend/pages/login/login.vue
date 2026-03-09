<template>
  <view class="page-wrap">
    <view class="muted">正在进入登录页...</view>
  </view>
</template>

<script>
export default {
  onLoad() {
    uni.redirectTo({ url: '/pages/account/login' });
  }
};
</script>
