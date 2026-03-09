<template><view class="page-wrap"><view class="muted">正在进入找人才...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=resume&title=找人才' }); } };
</script>
