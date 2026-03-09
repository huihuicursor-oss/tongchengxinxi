<template>
  <view class="page-wrap">
    <view class="page-title">同城发现</view>

    <view class="card" style="margin-bottom: 24rpx">
      <input v-model="form.title" class="input" placeholder="帖子标题" />
      <view style="height: 16rpx"></view>
      <textarea v-model="form.content" class="textarea" placeholder="分享你的同城动态"></textarea>
      <view style="height: 16rpx"></view>
      <view class="primary-btn" @click="publish">发布帖子</view>
    </view>

    <view
      v-for="item in list"
      :key="item.id"
      class="card list-card"
      @click="openDetail(item.id)"
    >
      <view class="item-head">
        <text class="item-title">{{ item.title }}</text>
        <text class="topic">{{ item.topic }}</text>
      </view>
      <view class="muted">{{ item.content }}</view>
      <view class="meta-row">
        <text>{{ item.author }}</text>
        <text>{{ item.likes }} 赞</text>
        <text>{{ item.comments_count }} 评论</text>
      </view>
    </view>
  </view>
</template>

<script>
import { fetchDiscoveryFeed, createDiscovery } from '@/api/index.js';

export default {
  data() {
    return {
      list: [],
      form: {
        title: '',
        content: ''
      }
    };
  },
  onLoad() {
    this.loadData();
  },
  methods: {
    loadData() {
      fetchDiscoveryFeed().then(data => {
        this.list = data;
      });
    },
    openDetail(id) {
      uni.navigateTo({ url: `/pages/content/detail?id=${id}&type=discovery` });
    },
    publish() {
      if (!this.form.title || !this.form.content) {
        uni.showToast({ title: '请填写标题和内容', icon: 'none' });
        return;
      }
      createDiscovery(this.form).then(() => {
        uni.showToast({ title: '发帖成功', icon: 'success' });
        this.form.title = '';
        this.form.content = '';
      });
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

.topic {
  color: #845ef7;
}

.meta-row {
  margin-top: 16rpx;
  color: #8a8a8a;
  font-size: 24rpx;
}
</style>
