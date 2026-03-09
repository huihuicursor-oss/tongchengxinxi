<template><view class="page-wrap"><view class="muted">正在进入需求打听...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=ask&title=需求打听' }); } };
</script>
