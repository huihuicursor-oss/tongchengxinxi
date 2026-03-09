<template><view class="page-wrap"><view class="muted">正在进入农林牧渔...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=farming&title=农林牧渔' }); } };
</script>
