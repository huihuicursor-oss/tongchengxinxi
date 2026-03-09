<template><view class="page-wrap"><view class="muted">正在进入二手车...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=car&title=二手车' }); } };
</script>
