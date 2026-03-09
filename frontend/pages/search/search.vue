<template>
  <view class="page-wrap">
    <view class="page-title">搜索信息</view>
    <input v-model="keyword" class="input" placeholder="职位、房源、车辆、物品..." @confirm="loadData" />
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
      </view>
    </view>
  </view>
</template>

<script>
import { fetchSearchContent } from '@/api/index.js';

export default {
  data() {
    return {
      keyword: '',
      list: []
    };
  },
  methods: {
    loadData() {
      fetchSearchContent(this.keyword).then(data => {
        this.list = data;
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
  margin-top: 12rpx;
  color: #8a8a8a;
  font-size: 24rpx;
}
</style>
