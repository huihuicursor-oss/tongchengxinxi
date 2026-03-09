<template><view class="page-wrap"><view class="muted">正在进入发布推广...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=promote&title=发布推广' }); } };
</script>
