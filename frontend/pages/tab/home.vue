<template>
  <view class="page-wrap">
    <view class="page-title">{{ bootstrap.project.name }}</view>
    <view class="card hero-card">
      <view class="hero-title">{{ currentBanner.title }}</view>
      <view class="muted">{{ currentBanner.desc }}</view>
      <image class="hero-image" :src="currentBanner.image" mode="aspectFill"></image>
    </view>

    <view class="section-title">
      <text>功能导航</text>
      <text class="muted">{{ bootstrap.default_city.name }}</text>
    </view>
    <view class="channel-grid">
      <view
        v-for="channel in bootstrap.channel_groups"
        :key="channel.key"
        class="card channel-card"
        @click="openChannel(channel)"
      >
        <view class="channel-name">{{ channel.label }}</view>
        <view class="muted small">{{ channel.summary }}</view>
      </view>
    </view>

    <view class="section-title">
      <text>最新发布</text>
      <text class="muted" @click="openChannel({ key: 'job', label: '招聘求职' })">更多</text>
    </view>
    <view
      v-for="item in bootstrap.featured_content"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id, 'content')"
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

    <view class="section-title">
      <text>本地头条</text>
      <text class="muted" @click="goTab('/pages/tab/news')">更多</text>
    </view>
    <view
      v-for="item in bootstrap.headline_list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id, 'news')"
    >
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.summary }}</view>
    </view>

    <view class="section-title">
      <text>优选商家</text>
      <text class="muted" @click="goTab('/pages/tab/find')">全部</text>
    </view>
    <view class="merchant-scroll">
      <view
        v-for="item in bootstrap.merchant_list"
        :key="item.id"
        class="card merchant-card"
        @click="openMerchant(item.id)"
      >
        <view class="item-title">{{ item.name }}</view>
        <view class="muted">{{ item.category_name }}</view>
        <view class="muted">{{ item.summary }}</view>
      </view>
    </view>

    <view class="section-title">
      <text>圈子动态</text>
      <text class="muted" @click="goTab('/pages/discovery/index')">进入</text>
    </view>
    <view
      v-for="item in bootstrap.discovery_list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id, 'discovery')"
    >
      <view class="item-title">{{ item.title }}</view>
      <view class="muted">{{ item.content }}</view>
    </view>

    <view class="section-title">
      <text>快速切换</text>
    </view>
    <view class="nav-row">
      <view v-for="tab in tabs" :key="tab.path" class="nav-chip" @click="goTab(tab.path)">{{ tab.name }}</view>
    </view>
  </view>
</template>

<script>
import { fetchBootstrap } from '@/api/index.js';
import { TABS } from '@/common/config.js';

export default {
  data() {
    return {
      tabs: TABS,
      bootstrap: {
        project: { name: 'SybMenhu 同城门户' },
        default_city: { name: '上海市' },
        channel_groups: [],
        featured_content: [],
        headline_list: [],
        merchant_list: [],
        discovery_list: []
      }
    };
  },
  computed: {
    currentBanner() {
      return this.bootstrap.banner && this.bootstrap.banner.length
        ? this.bootstrap.banner[0]
        : { title: '', desc: '', image: '' };
    }
  },
  onLoad() {
    fetchBootstrap().then(data => {
      this.bootstrap = data;
    });
  },
  methods: {
    goTab(path) {
      uni.reLaunch({ url: path });
    },
    openChannel(channel) {
      uni.navigateTo({ url: `/pages/content/list?channel=${channel.key}&title=${channel.label}` });
    },
    openDetail(id, type) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=${type}` });
    },
    openMerchant(id) {
      uni.navigateTo({ url: `/pages/merchant/detail?id=${id}` });
    }
  }
};
</script>

<style>
.hero-card {
  overflow: hidden;
}

.hero-title {
  font-size: 36rpx;
  font-weight: 700;
}

.hero-image {
  width: 100%;
  height: 280rpx;
  border-radius: 20rpx;
  margin-top: 20rpx;
}

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
  font-weight: 600;
}

.small {
  margin-top: 10rpx;
  font-size: 24rpx;
}

.item-head,
.meta-row {
  display: flex;
  justify-content: space-between;
  gap: 16rpx;
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

.merchant-scroll {
  display: flex;
  flex-direction: column;
  gap: 20rpx;
}

.merchant-card {
  min-height: 160rpx;
}
</style>
