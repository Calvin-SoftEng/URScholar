<template>
    <div class="grid grid-cols-4 gap-3 px-3 py-3 h-full box-border">
        <!-- 75% Column -->
        <div class="col-span-3 h-full flex flex-col space-y-3">
            <!-- First Layer with 2 Column Cards -->
            <div class="grid grid-cols-2 gap-3 h-[35%]">
            <div class="bg-black h-full rounded"></div>
            <div class="bg-black h-full rounded"></div>
            </div>
            <!-- Second Layer with Single Card -->
            <div class="bg-black box-border rounded h-[65%]">
            <!-- <ContentComponent /> -->
            </div>
        </div>
        <!-- 25% Column -->
        <div class="w-full h-full flex flex-col space-y-3">
            <div class="bg-black rounded h-[35%]"></div>
            <div class="bg-black rounded h-[65%]"></div>
        </div>
    </div>

</template>

<script >
// import ContentComponent from './admin/admin_dashboard.vue';

export default {
// components: {
//     ContentComponent
// }
}

</script>

<style>

</style>