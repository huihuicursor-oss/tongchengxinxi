<template>
  <view class="page-wrap">
    <view class="muted">正在进入帖子详情...</view>
  </view>
</template>

<script>
export default {
  onLoad(options) {
    uni.redirectTo({ url: `/pages/content/detail?id=${options.id || 'd-1'}&type=discovery` });
  }
};
</script>
