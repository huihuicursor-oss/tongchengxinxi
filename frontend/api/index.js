import { API_BASE_URL, USE_MOCK } from '@/common/config.js';
import {
  getBootstrap,
  getChannels,
  getCities,
  getContentList,
  getContentDetail,
  getNewsList,
  getNewsDetail,
  getMerchantList,
  getMerchantDetail,
  getMerchantComments,
  addMerchantComment,
  applyMerchant,
  getDiscoveryFeed,
  getDiscoveryDetail,
  getTopics,
  getTrendsFriends,
  getUserDashboard,
  getCollections,
  getMessages,
  getHelpArticles,
  getVipInfo,
  login,
  register,
  publishContent,
  publishDiscovery,
  searchContent,
  searchMerchants,
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

export function fetchCities() {
  return USE_MOCK ? Promise.resolve(getCities()) : request('/cities');
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

export function fetchMerchantComments(id) {
  return USE_MOCK ? Promise.resolve(getMerchantComments(id)) : request(`/merchants/${id}/comments`);
}

export function createMerchantComment(payload) {
  return USE_MOCK ? Promise.resolve(addMerchantComment(payload)) : request('/merchant-comment', 'POST', payload);
}

export function createMerchantApply(payload) {
  return USE_MOCK ? Promise.resolve(applyMerchant(payload)) : request('/merchant-apply', 'POST', payload);
}

export function fetchDiscoveryFeed() {
  return USE_MOCK ? Promise.resolve(getDiscoveryFeed()) : request('/discovery');
}

export function fetchDiscoveryDetail(id) {
  return USE_MOCK ? Promise.resolve(getDiscoveryDetail(id)) : request(`/discovery/${id}`);
}

export function fetchTopics() {
  return USE_MOCK ? Promise.resolve(getTopics()) : request('/discovery/topics');
}

export function fetchTrendsFriends() {
  return USE_MOCK ? Promise.resolve(getTrendsFriends()) : request('/discovery/friends');
}

export function createDiscovery(payload) {
  return USE_MOCK ? Promise.resolve(publishDiscovery(payload)) : request('/discovery', 'POST', payload);
}

export function fetchUserDashboard() {
  return USE_MOCK ? Promise.resolve(getUserDashboard()) : request('/user/dashboard');
}

export function fetchCollections() {
  return USE_MOCK ? Promise.resolve(getCollections()) : request('/user/collections');
}

export function fetchMessages() {
  return USE_MOCK ? Promise.resolve(getMessages()) : request('/user/messages');
}

export function fetchHelpArticles() {
  return USE_MOCK ? Promise.resolve(getHelpArticles()) : request('/user/help');
}

export function fetchVipInfo() {
  return USE_MOCK ? Promise.resolve(getVipInfo()) : request('/user/vip');
}

export function loginByPassword(payload) {
  return USE_MOCK ? Promise.resolve(login(payload)) : request('/auth/login', 'POST', payload);
}

export function registerByPassword(payload) {
  return USE_MOCK ? Promise.resolve(register(payload)) : request('/auth/register', 'POST', payload);
}

export function fetchSearchContent(keyword) {
  return USE_MOCK ? Promise.resolve(searchContent(keyword)) : request('/search/content', 'GET', { keyword });
}

export function fetchSearchMerchants(keyword) {
  return USE_MOCK ? Promise.resolve(searchMerchants(keyword)) : request('/search/merchants', 'GET', { keyword });
}

export function createFeedback(payload) {
  return USE_MOCK ? Promise.resolve(submitFeedback(payload)) : request('/user/feedback', 'POST', payload);
}
