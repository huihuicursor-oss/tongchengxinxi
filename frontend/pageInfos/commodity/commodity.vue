<template><view class="page-wrap"><view class="muted">正在进入物品交易...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=commodity&title=物品交易' }); } };
</script>
