<template>
  <view class="page-wrap">
    <view class="page-title">发布 {{ channel.label || pageTitle }}</view>
    <view v-for="field in channel.fields || []" :key="field.key" style="margin-bottom: 20rpx">
      <view class="muted" style="margin-bottom: 10rpx">{{ field.label }}</view>
      <input
        v-if="field.type !== 'textarea'"
        v-model="form[field.key]"
        class="input"
        :placeholder="`请输入${field.label}`"
      />
      <textarea
        v-else
        v-model="form[field.key]"
        class="textarea"
        :placeholder="`请输入${field.label}`"
      ></textarea>
    </view>

    <view class="primary-btn" @click="submit">提交发布</view>
  </view>
</template>

<script>
import { fetchChannels, createContent } from '@/api/index.js';

export default {
  data() {
    return {
      channelKey: '',
      pageTitle: '信息',
      channel: {},
      form: {}
    };
  },
  onLoad(options) {
    this.channelKey = options.channel || 'job';
    this.pageTitle = options.title || '信息';
    fetchChannels().then(data => {
      this.channel = data[this.channelKey] || {};
      this.initForm();
    });
  },
  methods: {
    initForm() {
      const form = {};
      (this.channel.fields || []).forEach(field => {
        form[field.key] = '';
      });
      this.form = form;
    },
    submit() {
      for (let i = 0; i < (this.channel.fields || []).length; i += 1) {
        const field = this.channel.fields[i];
        if (field.required && !this.form[field.key]) {
          uni.showToast({ title: `${field.label}不能为空`, icon: 'none' });
          return;
        }
      }
      createContent({
        channel: this.channelKey,
        ...this.form
      }).then(result => {
        uni.showToast({ title: '发布成功', icon: 'success' });
        setTimeout(() => {
          uni.redirectTo({
            url: `/pages/content/list?channel=${this.channelKey}&title=${this.channel.label || this.pageTitle}`
          });
        }, 800);
      });
    }
  }
};
</script>
