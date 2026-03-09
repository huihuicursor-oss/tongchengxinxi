<template>
  <view class="app-root">
    <slot />
  </view>
</template>

<script>
export default {
  onLaunch() {
    console.log('menhu app launch');
  }
};
</script>

<style>
page {
  background: #f4f6f8;
  color: #222;
  font-size: 28rpx;
}

.page-wrap {
  min-height: 100vh;
  padding: 24rpx;
  box-sizing: border-box;
}

.card {
  background: #fff;
  border-radius: 24rpx;
  padding: 24rpx;
  box-sizing: border-box;
  box-shadow: 0 12rpx 40rpx rgba(0, 0, 0, 0.04);
}

.page-title {
  font-size: 40rpx;
  font-weight: 700;
  margin-bottom: 24rpx;
}

.section-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 32rpx;
  font-weight: 600;
  margin: 32rpx 0 20rpx;
}

.nav-row {
  display: flex;
  flex-wrap: wrap;
  gap: 16rpx;
}

.nav-chip {
  padding: 14rpx 24rpx;
  background: #fff;
  border-radius: 999rpx;
  font-size: 26rpx;
}

.list-card {
  margin-bottom: 20rpx;
}

.muted {
  color: #8a8a8a;
}

.primary-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 88rpx;
  background: #f26628;
  color: #fff;
  border-radius: 20rpx;
  font-size: 30rpx;
}

.input {
  width: 100%;
  min-height: 88rpx;
  background: #fff;
  border-radius: 20rpx;
  padding: 0 24rpx;
  box-sizing: border-box;
}

.textarea {
  width: 100%;
  min-height: 220rpx;
  background: #fff;
  border-radius: 20rpx;
  padding: 24rpx;
  box-sizing: border-box;
}
</style>
