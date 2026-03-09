import { API_BASE_URL, USE_MOCK } from '@/common/config.js';
import {
  getBootstrap,
  getChannels,
  getContentList,
  getContentDetail,
  getNewsList,
  getNewsDetail,
  getMerchantList,
  getMerchantDetail,
  getDiscoveryFeed,
  getDiscoveryDetail,
  getUserDashboard,
  login,
  publishContent,
  publishDiscovery,
  submitFeedback
} from '@/common/mock-data.js';

function request(url, method = 'GET', data = {}) {
  return new Promise((resolve, reject) => {
    uni.request({
      url: `${API_BASE_URL}${url}`,
      method,
      data,
      success: res => {
        resolve(res.data);
      },
      fail: reject
    });
  });
}

export function fetchBootstrap() {
  return USE_MOCK ? Promise.resolve(getBootstrap()) : request('/bootstrap');
}

export function fetchChannels() {
  return USE_MOCK ? Promise.resolve(getChannels()) : request('/channels');
}

export function fetchContentList(params) {
  return USE_MOCK ? Promise.resolve(getContentList(params)) : request('/content', 'GET', params);
}

export function fetchContentDetail(id) {
  return USE_MOCK ? Promise.resolve(getContentDetail(id)) : request(`/content/${id}`);
}

export function createContent(payload) {
  return USE_MOCK ? Promise.resolve(publishContent(payload)) : request('/content', 'POST', payload);
}

export function fetchNewsList() {
  return USE_MOCK ? Promise.resolve(getNewsList()) : request('/news');
}

export function fetchNewsDetail(id) {
  return USE_MOCK ? Promise.resolve(getNewsDetail(id)) : request(`/news/${id}`);
}

export function fetchMerchantList(keyword) {
  return USE_MOCK ? Promise.resolve(getMerchantList(keyword)) : request('/merchants', 'GET', { keyword });
}

export function fetchMerchantDetail(id) {
  return USE_MOCK ? Promise.resolve(getMerchantDetail(id)) : request(`/merchants/${id}`);
}

export function fetchDiscoveryFeed() {
  return USE_MOCK ? Promise.resolve(getDiscoveryFeed()) : request('/discovery');
}

export function fetchDiscoveryDetail(id) {
  return USE_MOCK ? Promise.resolve(getDiscoveryDetail(id)) : request(`/discovery/${id}`);
}

export function createDiscovery(payload) {
  return USE_MOCK ? Promise.resolve(publishDiscovery(payload)) : request('/discovery', 'POST', payload);
}

export function fetchUserDashboard() {
  return USE_MOCK ? Promise.resolve(getUserDashboard()) : request('/user/dashboard');
}

export function loginByPassword(payload) {
  return USE_MOCK ? Promise.resolve(login(payload)) : request('/auth/login', 'POST', payload);
}

export function createFeedback(payload) {
  return USE_MOCK ? Promise.resolve(submitFeedback(payload)) : request('/user/feedback', 'POST', payload);
}
