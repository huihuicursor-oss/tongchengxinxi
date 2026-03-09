<template>
  <view class="page-wrap">
    <view class="page-title">注册账号</view>
    <view class="card">
      <input v-model="form.nickname" class="input" placeholder="请输入昵称" />
      <view style="height: 20rpx"></view>
      <input v-model="form.mobile" class="input" placeholder="请输入手机号" />
      <view style="height: 20rpx"></view>
      <input v-model="form.password" class="input" password placeholder="请输入密码" />
      <view style="height: 24rpx"></view>
      <view class="primary-btn" @click="submit">注册并登录</view>
    </view>
  </view>
</template>

<script>
import { registerByPassword } from '@/api/index.js';

export default {
  data() {
    return {
      form: {
        nickname: '',
        mobile: '',
        password: ''
      }
    };
  },
  methods: {
    submit() {
      if (!this.form.nickname || !this.form.mobile || !this.form.password) {
        uni.showToast({ title: '请完整填写注册信息', icon: 'none' });
        return;
      }
      registerByPassword(this.form).then(result => {
        const payload = result.data || result;
        uni.setStorageSync('token', payload.token);
        uni.setStorageSync('userinfo', payload.userinfo);
        uni.showToast({ title: '注册成功', icon: 'success' });
        setTimeout(() => {
          uni.reLaunch({ url: '/pages/tab/user' });
        }, 800);
      });
    }
  }
};
</script>
