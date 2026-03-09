<template>
  <view class="page-wrap">
    <view class="page-title">本地头条</view>
    <view v-for="item in list" :key="item.id" class="card list-card" @click="openDetail(item.id)">
      <image class="cover" :src="item.cover" mode="aspectFill"></image>
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.summary }}</view>
      <view class="muted meta">{{ item.created_at }}</view>
    </view>

    <view class="nav-row">
      <view class="nav-chip" @click="go('/pages/tab/home')">首页</view>
      <view class="nav-chip" @click="go('/pages/tab/find')">商家</view>
      <view class="nav-chip" @click="go('/pages/discovery/index')">发现</view>
      <view class="nav-chip" @click="go('/pages/tab/user')">我的</view>
    </view>
  </view>
</template>

<script>
import { fetchNewsList } from '@/api/index.js';

export default {
  data() {
    return {
      list: []
    };
  },
  onLoad() {
    fetchNewsList().then(data => {
      this.list = data;
    });
  },
  methods: {
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=news` });
    },
    go(path) {
      uni.reLaunch({ url: path });
    }
  }
};
</script>

<style>
.cover {
  width: 100%;
  height: 280rpx;
  border-radius: 18rpx;
  margin-bottom: 20rpx;
}

.item-title {
  font-size: 32rpx;
  font-weight: 700;
  margin-bottom: 12rpx;
}

.meta {
  margin-top: 12rpx;
}
</style>
