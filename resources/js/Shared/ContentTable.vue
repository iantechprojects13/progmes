<template>
    <div class="w-full pb-2 bg-white overflow-x-auto text-base h-auto relative">
        <table class="w-full text-left px-3 relative">
            <tr
                class="text-gray-700 rounded-full whitespace-nowrap bg-gray-200 sticky -top-0.5 z-20"
            >
                <slot name="table-head"></slot>
            </tr>
            <tbody>
                <slot name="table-body"></slot>
            </tbody>
        </table>
    </div>
</template>
