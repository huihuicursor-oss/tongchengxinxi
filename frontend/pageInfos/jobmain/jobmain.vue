<template><view class="page-wrap"><view class="muted">正在进入招聘详情...</view></view></template>
<script>
export default { onLoad(options) { uni.redirectTo({ url: `/pages/content/detail?id=${options.id || 'c-job-1'}&type=content` }); } };
</script>
