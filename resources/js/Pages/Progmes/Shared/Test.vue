<template>
    <Head title="Test" />
    <div>Test Page</div>
    <div>
        <pre>
            {{ items }}
        </pre>
    </div>
</template>

<script setup>

defineProps([
    'evaluation',
    'items',
]);

</script>
