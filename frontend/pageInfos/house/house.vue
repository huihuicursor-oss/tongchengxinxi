<template><view class="page-wrap"><view class="muted">正在进入房屋租售...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channels=house_rent,house_sell&title=房屋租售' }); } };
</script>
