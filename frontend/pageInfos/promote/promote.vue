<template><view class="page-wrap"><view class="muted">正在进入本地推广...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=promote&title=本地推广' }); } };
</script>
