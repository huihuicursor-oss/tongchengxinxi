<template>
  <view class="page-wrap">
    <view class="page-title">我的收藏</view>
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
    </view>
  </view>
</template>

<script>
import { fetchCollections } from '@/api/index.js';

export default {
  data() {
    return {
      list: []
    };
  },
  onLoad() {
    fetchCollections().then(data => {
      this.list = data;
    });
  },
  methods: {
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=content` });
    }
  }
};
</script>

<style>
.item-head {
  display: flex;
  justify-content: space-between;
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
