<template>
  <view class="page-wrap">
    <view class="page-title">商家列表</view>
    <input v-model="keyword" class="input" placeholder="搜索店铺" @confirm="loadData" />
    <view style="height: 20rpx"></view>
    <view class="primary-btn" @click="loadData">搜索</view>

    <view
      v-for="item in list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-head">
        <text class="item-title">{{ item.name }}</text>
        <text class="score">{{ item.score }} 分</text>
      </view>
      <view class="muted">{{ item.category_name }}</view>
      <view class="muted">{{ item.summary }}</view>
      <view class="muted meta">{{ item.address }}</view>
    </view>
  </view>
</template>

<script>
import { fetchMerchantList } from '@/api/index.js';

export default {
  data() {
    return {
      keyword: '',
      list: []
    };
  },
  onLoad() {
    this.loadData();
  },
  methods: {
    loadData() {
      fetchMerchantList(this.keyword).then(data => {
        this.list = data;
      });
    },
    openDetail(id) {
      uni.navigateTo({ url: `/pages/merchant/detail?id=${id}` });
    }
  }
};
</script>

<style>
.item-head {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10rpx;
}

.item-title {
  font-size: 30rpx;
  font-weight: 700;
}

.score {
  color: #f26628;
  font-weight: 700;
}

.meta {
  margin-top: 12rpx;
}
</style>
