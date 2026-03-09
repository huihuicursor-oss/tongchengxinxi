<template><view class="page-wrap"><view class="muted">正在进入发布物品...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=commodity&title=发布物品' }); } };
</script>
