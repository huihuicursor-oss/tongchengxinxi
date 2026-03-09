<template><view class="page-wrap"><view class="muted">正在进入全部招聘...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/list?channel=job&title=全部招聘' }); } };
</script>
