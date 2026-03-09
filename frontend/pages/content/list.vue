<template>
  <view class="page-wrap">
    <view class="page-title">{{ pageTitle }}</view>
    <input v-model="keyword" class="input" placeholder="搜索关键词" @confirm="loadData" />
    <view style="height: 20rpx"></view>
    <view class="primary-btn" @click="loadData">搜索</view>

    <view style="height: 24rpx"></view>
    <view
      v-for="item in list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-head">
        <text class="item-title">{{ item.title }}</text>
        <text class="item-price">{{ item.price }}</text>
      </view>
      <view class="muted">{{ item.summary }}</view>
      <view class="meta-row">
        <text>{{ item.channel_name }}</text>
        <text>{{ item.city }}</text>
        <text>{{ item.created_at }}</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchContentList } from '@/api/index.js';

export default {
  data() {
    return {
      channel: '',
      channels: [],
      pageTitle: '信息列表',
      keyword: '',
      list: []
    };
  },
  onLoad(options) {
    this.channel = options.channel || '';
    this.channels = options.channels ? options.channels.split(',') : [];
    this.pageTitle = options.title || '信息列表';
    this.loadData();
  },
  methods: {
    loadData() {
      fetchContentList({
        channel: this.channel,
        channels: this.channels,
        keyword: this.keyword
      }).then(data => {
        this.list = Array.isArray(data) ? data : data.list;
      });
    },
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=content` });
    }
  }
};
</script>

<style>
.item-head,
.meta-row {
  display: flex;
  justify-content: space-between;
  gap: 16rpx;
}

.item-title {
  font-size: 30rpx;
  font-weight: 700;
}

.item-price {
  color: #f26628;
  font-weight: 700;
}

.meta-row {
  margin-top: 16rpx;
  color: #8a8a8a;
  font-size: 24rpx;
}
</style>
