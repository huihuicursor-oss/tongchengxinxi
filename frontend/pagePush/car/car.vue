<template><view class="page-wrap"><view class="muted">正在进入发布车辆...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=car&title=发布车辆' }); } };
</script>
