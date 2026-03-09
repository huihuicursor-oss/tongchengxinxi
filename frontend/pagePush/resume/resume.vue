<template><view class="page-wrap"><view class="muted">正在进入编辑简历...</view></view></template>
<script>
export default { onLoad() { uni.redirectTo({ url: '/pages/content/publish?channel=resume&title=编辑简历' }); } };
</script>
