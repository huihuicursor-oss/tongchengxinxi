<template>
  <view class="page-wrap">
    <view class="page-title">搜索店铺</view>
    <input v-model="keyword" class="input" placeholder="商家名称、类目" @confirm="loadData" />
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
        <text class="item-title">{{ item.name }}</text>
        <text class="item-price">{{ item.score }} 分</text>
      </view>
      <view class="muted">{{ item.category_name }}</view>
      <view class="muted">{{ item.summary }}</view>
    </view>
  </view>
</template>

<script>
import { fetchSearchMerchants } from '@/api/index.js';

export default {
  data() {
    return {
      keyword: '',
      list: []
    };
  },
  methods: {
    loadData() {
      fetchSearchMerchants(this.keyword).then(data => {
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
</style>
