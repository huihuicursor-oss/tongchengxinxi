<template>
  <view class="page-wrap">
    <view class="card">
      <view class="page-title">{{ detail.title || detail.name }}</view>
      <view v-if="detail.cover">
        <image class="cover" :src="detail.cover" mode="aspectFill"></image>
      </view>
      <view v-if="detail.price" class="price">{{ detail.price }}</view>
      <view class="muted" style="margin-bottom: 20rpx">{{ detail.created_at }}</view>
      <view class="detail-text">{{ mainContent }}</view>
    </view>

    <view v-if="detail.detail && detail.detail.contact" class="card" style="margin-top: 24rpx">
      <view class="item-title">联系信息</view>
      <view class="muted">联系人：{{ detail.detail.contact.name }}</view>
      <view class="muted">电话：{{ detail.detail.contact.mobile }}</view>
      <view class="muted">微信：{{ detail.detail.contact.wechat }}</view>
    </view>

    <view v-if="commentList.length" class="card" style="margin-top: 24rpx">
      <view class="item-title">评论 / 回复</view>
      <view v-for="(comment, index) in commentList" :key="index" class="comment-row">
        <view class="comment-user">{{ comment.user }}</view>
        <view class="muted">{{ comment.content }}</view>
      </view>
    </view>
  </view>
</template>

<script>
import {
  fetchContentDetail,
  fetchNewsDetail,
  fetchDiscoveryDetail
} from '@/api/index.js';

export default {
  data() {
    return {
      detail: {},
      type: 'content'
    };
  },
  computed: {
    mainContent() {
      if (this.type === 'content') {
        return this.detail.detail ? this.detail.detail.content : '';
      }
      return this.detail.content || this.detail.summary || '';
    },
    commentList() {
      if (this.type === 'discovery') {
        return this.detail.comments || [];
      }
      return [];
    }
  },
  onLoad(options) {
    this.type = options.type || 'content';
    this.loadData(options.id);
  },
  methods: {
    loadData(id) {
      const actionMap = {
        content: fetchContentDetail,
        news: fetchNewsDetail,
        discovery: fetchDiscoveryDetail
      };
      actionMap[this.type](id).then(data => {
        this.detail = data || {};
      });
    }
  }
};
</script>

<style>
.cover {
  width: 100%;
  height: 320rpx;
  border-radius: 20rpx;
  margin-bottom: 20rpx;
}

.price {
  font-size: 36rpx;
  color: #f26628;
  font-weight: 700;
  margin-bottom: 16rpx;
}

.detail-text {
  line-height: 1.8;
}

.item-title {
  font-size: 30rpx;
  font-weight: 700;
  margin-bottom: 16rpx;
}

.comment-row {
  padding: 16rpx 0;
  border-bottom: 1rpx solid #f0f0f0;
}

.comment-user {
  font-weight: 600;
  margin-bottom: 8rpx;
}
</style>
