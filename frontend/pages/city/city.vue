<template>
  <view class="page-wrap">
    <view class="page-title">城市切换</view>
    <view v-for="group in cities" :key="group.id" class="card list-card">
      <view class="item-title">{{ group.name }}</view>
      <view v-for="city in group.children" :key="city.id" class="city-block">
        <view class="city-name" @click="selectCity(city.name)">{{ city.name }}</view>
        <view class="district-row">
          <text
            v-for="district in city.children || []"
            :key="district.id"
            class="district-chip"
            @click="selectCity(city.name)"
          >
            {{ district.name }}
          </text>
        </view>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchCities } from '@/api/index.js';

export default {
  data() {
    return {
      cities: []
    };
  },
  onLoad() {
    fetchCities().then(data => {
      this.cities = data;
    });
  },
  methods: {
    selectCity(name) {
      uni.setStorageSync('currentCity', name);
      uni.showToast({ title: `已切换到${name}`, icon: 'none' });
      setTimeout(() => {
        uni.navigateBack({ delta: 1 });
      }, 600);
    }
  }
};
</script>

<style>
.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 18rpx;
}

.city-block + .city-block {
  margin-top: 20rpx;
}

.city-name {
  font-weight: 600;
  margin-bottom: 12rpx;
}

.district-row {
  display: flex;
  flex-wrap: wrap;
  gap: 12rpx;
}

.district-chip {
  padding: 10rpx 18rpx;
  background: #f4f6f8;
  border-radius: 999rpx;
  font-size: 24rpx;
}
</style>
