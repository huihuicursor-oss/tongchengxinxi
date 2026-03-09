<template><view class="page-wrap"><view class="muted">正在进入发布需求...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=ask&title=发布需求' }); } };
</script>
