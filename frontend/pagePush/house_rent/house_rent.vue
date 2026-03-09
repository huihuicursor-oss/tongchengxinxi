<template><view class="page-wrap"><view class="muted">正在进入房屋出租发布...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=house_rent&title=房屋出租' }); } };
</script>
