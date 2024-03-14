<template>

    <Head title="Test" />
    <div class="w-96">
        <progress-bar></progress-bar>
    </div>
</template>

<script setup>

    defineProps([
        'evaluation',
        'items',
    ]);

</script>