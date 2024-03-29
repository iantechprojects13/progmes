<template>
    <div class="w-full pb-2 bg-white text-base h-auto relative">
        <table class="w-full text-left whitespace-nowrap relative">
            <tr
                class="text-gray-700 border-b border-gray-300 sticky -top-0.5 z-20"
            >
                <slot name="table-head"></slot>
            </tr>
            <tbody class="px-2">
                <slot name="table-body"></slot>
            </tbody>
        </table>
    </div>
</template>
