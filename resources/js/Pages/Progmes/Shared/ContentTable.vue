<template>
    <div class="w-full px-3 text-sm">
        <table class="w-full text-left whitespace-nowrap overflow-auto">
            <thead class="uppercase w-full bg-white sticky top-0 z-20">
                <tr>
                    <slot name="table-head"></slot>
                </tr>
            </thead>
            <tbody>
                <slot name="table-body"></slot>
            </tbody>
        </table>
    </div>
</template>