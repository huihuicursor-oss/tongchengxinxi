<template><view class="page-wrap"><view class="muted">正在进入拼车出行...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=carpool&title=拼车出行' }); } };
</script>
