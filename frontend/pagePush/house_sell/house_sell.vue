<template><view class="page-wrap"><view class="muted">正在进入房屋出售发布...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=house_sell&title=房屋出售' }); } };
</script>
