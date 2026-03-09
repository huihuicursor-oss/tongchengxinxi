<template>
  <view class="page-wrap">
    <view class="page-title">登录门户系统</view>
    <view class="card">
      <view class="muted" style="margin-bottom: 12rpx">演示账号：18888888881</view>
      <view class="muted" style="margin-bottom: 24rpx">演示密码：18888888881</view>
      <input v-model="form.mobile" class="input" placeholder="请输入手机号" />
      <view style="height: 20rpx"></view>
      <input v-model="form.password" class="input" password placeholder="请输入密码" />
      <view style="height: 24rpx"></view>
      <view class="primary-btn" @click="submit">登录</view>
    </view>
  </view>
</template>

<script>
import { loginByPassword } from '@/api/index.js';

export default {
  data() {
    return {
      form: {
        mobile: '18888888881',
        password: '18888888881'
      }
    };
  },
  methods: {
    submit() {
      loginByPassword(this.form).then(result => {
        if (!result.ok && !result.data) {
          uni.showToast({ title: result.message || '登录失败', icon: 'none' });
          return;
        }
        const payload = result.data || result;
        uni.setStorageSync('token', payload.token);
        uni.setStorageSync('userinfo', payload.userinfo);
        uni.showToast({ title: '登录成功', icon: 'success' });
        setTimeout(() => {
          uni.reLaunch({ url: '/pages/tab/user' });
        }, 800);
      });
    }
  }
};
</script>
