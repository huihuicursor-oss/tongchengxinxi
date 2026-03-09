<template>
  <view class="page-wrap">
    <view class="page-title">选择发布类型</view>
    <view class="channel-grid">
      <view
        v-for="channel in channelList"
        :key="channel.key"
        class="card channel-card"
        @click="openForm(channel)"
      >
        <view class="channel-name">{{ channel.label }}</view>
        <view class="muted">{{ channel.summary }}</view>
      </view>
    </view>

    <view class="card tips-box">
      <view class="item-title">发布说明</view>
      <view class="muted">支持招聘、房屋、车辆、物品、农林牧渔、需求、拼车和本地推广等栏目。</view>
      <view class="muted">你可以先用 mock 数据预览，接真实接口时仅需关闭 USE_MOCK。</view>
    </view>

    <view class="primary-btn" @click="goLogin">登录后管理发布</view>
  </view>
</template>

<script>
import { fetchChannels } from '@/api/index.js';

export default {
  data() {
    return {
      channelList: []
    };
  },
  onLoad() {
    fetchChannels().then(data => {
      this.channelList = Object.keys(data).map(key => data[key]);
    });
  },
  methods: {
    openForm(channel) {
      uni.navigateTo({ url: `/pages/content/publish?channel=${channel.key}&title=${channel.label}` });
    },
    goLogin() {
      uni.navigateTo({ url: '/pages/account/login' });
    }
  }
};
</script>

<style>
.channel-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20rpx;
}

.channel-card {
  min-height: 160rpx;
}

.channel-name,
.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 12rpx;
}

.tips-box {
  margin: 28rpx 0;
}
</style>
