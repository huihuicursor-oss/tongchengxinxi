<template><view class="page-wrap"><view class="muted">正在进入发布农林牧渔...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=farming&title=发布农林牧渔' }); } };
</script>
