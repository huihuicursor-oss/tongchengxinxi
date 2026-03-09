<template><view class="page-wrap"><view class="muted">正在进入发布顺风车...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=carpool&title=发布顺风车' }); } };
</script>
