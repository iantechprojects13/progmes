<template>
    <div class="w-full px-0 pb-2 bg-white text-base h-auto relative">
        <table class="w-full text-left whitespace-nowrap relative">
            <tr class="bg-white border-b border-gray-400 sticky -top-0.5 z-20">
                <slot name="table-head"></slot>
            </tr>
            <tbody>
                <slot name="table-body"></slot>
            </tbody>
        </table>
    </div>
</template>