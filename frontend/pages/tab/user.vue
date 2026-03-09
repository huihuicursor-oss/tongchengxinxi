<template>
  <view class="page-wrap">
    <view class="card user-card">
      <image class="avatar" :src="dashboard.user.avatar" mode="aspectFill"></image>
      <view class="user-info">
        <view class="user-name">{{ dashboard.user.nickname }}</view>
        <view class="muted">{{ dashboard.user.mobile }} · {{ dashboard.user.city }}</view>
        <view class="vip">{{ dashboard.user.vip_name }}</view>
      </view>
    </view>

    <view class="section-title">
      <text>我的数据</text>
      <text class="muted" @click="goLogin">切换账号</text>
    </view>
    <view class="stats-grid">
      <view v-for="item in dashboard.stats" :key="item.label" class="card stat-card">
        <view class="stat-value">{{ item.value }}</view>
        <view class="muted">{{ item.label }}</view>
      </view>
    </view>

    <view class="section-title">
      <text>最近发布</text>
    </view>
    <view
      v-for="item in dashboard.latest_publish"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.channel_name }} · {{ item.price }}</view>
    </view>

    <view class="section-title">
      <text>意见反馈</text>
    </view>
    <textarea v-model="feedback" class="textarea" placeholder="请输入你的建议或问题"></textarea>
    <view style="height: 20rpx"></view>
    <view class="primary-btn" @click="submit">提交反馈</view>
  </view>
</template>

<script>
import { fetchUserDashboard, createFeedback } from '@/api/index.js';

export default {
  data() {
    return {
      feedback: '',
      dashboard: {
        user: {},
        stats: [],
        latest_publish: []
      }
    };
  },
  onLoad() {
    fetchUserDashboard().then(data => {
      this.dashboard = data;
    });
  },
  methods: {
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=content` });
    },
    goLogin() {
      uni.navigateTo({ url: '/pages/account/login' });
    },
    submit() {
      createFeedback({ content: this.feedback }).then(result => {
        uni.showToast({ title: `已提交 ${result.ticket_no}`, icon: 'none' });
        this.feedback = '';
      });
    }
  }
};
</script>

<style>
.user-card {
  display: flex;
  align-items: center;
}

.avatar {
  width: 120rpx;
  height: 120rpx;
  border-radius: 50%;
  margin-right: 24rpx;
}

.user-name {
  font-size: 34rpx;
  font-weight: 700;
}

.vip {
  display: inline-flex;
  margin-top: 12rpx;
  padding: 8rpx 18rpx;
  background: #fff3bf;
  border-radius: 999rpx;
  color: #9c6b00;
  font-size: 24rpx;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20rpx;
}

.stat-card {
  text-align: center;
}

.stat-value {
  font-size: 38rpx;
  font-weight: 700;
  color: #f26628;
  margin-bottom: 12rpx;
}

.item-title {
  font-size: 30rpx;
  font-weight: 600;
  margin-bottom: 10rpx;
}
</style>
