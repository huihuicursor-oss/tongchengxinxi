<template>
  <view class="page-wrap">
    <view class="page-title">系统设置</view>
    <view v-for="item in menus" :key="item.path" class="card list-card setting-row" @click="go(item.path)">
      <text>{{ item.name }}</text>
      <text class="muted">进入</text>
    </view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      menus: [
        { name: '我的消息', path: '/pageUser/msg/msg' },
        { name: '我的收藏', path: '/pageUser/collect/collect' },
        { name: '帮助中心', path: '/pageUser/help/help' },
        { name: '意见反馈', path: '/pageUser/feedback/feedback' },
        { name: '我的会员', path: '/pageUser/vip/vip' },
        { name: '城市切换', path: '/pages/city/city' }
      ]
    };
  },
  methods: {
    go(path) {
      uni.navigateTo({ url: path });
    }
  }
};
</script>

<style>
.setting-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
