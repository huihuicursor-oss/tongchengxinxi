<template><view class="page-wrap"><view class="muted">正在进入生意转让...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=transfer&title=生意转让' }); } };
</script>
