<template><view class="page-wrap"><view class="muted">正在进入发布职位...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=job&title=发布职位' }); } };
</script>
