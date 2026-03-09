<template>
  <view class="page-wrap">
    <view class="muted">正在进入商家列表...</view>
  </view>
</template>

<script>
export default {
  onLoad() {
    uni.redirectTo({ url: '/pages/tab/find' });
  }
};
</script>
