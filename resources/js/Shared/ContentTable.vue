<template>
  <div class="relative overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-white">
        <tr class="text-left">
          <slot name="table-head"></slot>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-gray-200">
        <slot name="table-body"></slot>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
.relative {
  position: relative;
}

/* Default padding for all cells */
:deep(td) {
  padding: 0.75rem 1rem; /* py-3 px-4 */
  border-bottom: 1px solid #d6d8e1;
  vertical-align: top; /* Added vertical alignment top */
}

/* Styling for all th elements */
:deep(th) {
  padding: 0.75rem 1rem; /* py-3 px-4 */
  text-align: left;
  font-size: 1rem; /* text-base */
  font-weight: 700; /* font-bold */
  color: #1f2937; /* text-gray-800 */
  border-bottom: 1px solid #d6d8e1;
}

/* Reset specific styles for the last th (action column) */
:deep(th:last-child) {
  /* Keep only the padding and position properties */
  font-weight: 700; /* Keep the font-bold */
  /* Other styles will be overridden by the sticky styles below */
}

/* Striped rows - every even row with more noticeable but still light color */
:deep(tr:nth-child(even)) {
  background-color: #f1f5f9; /* slate-100, slightly more visible than gray-50 */
}

/* Hover effect for all rows */
:deep(tbody tr:hover) {
  background-color: #e5e7eb; /* gray-200 */
}

/* Style for the fixed action column - align content to the right */
:deep(th:last-child),
:deep(td:last-child) {
  position: sticky;
  right: 0;
  z-index: 10;
  background-color: white;
  box-shadow: -2px 0 5px rgba(0, 0, 0, 0.1);
  text-align: right; /* Align content to the right */
  white-space: nowrap; /* Prevent text from wrapping */
  border-bottom: 1px solid #d6d8e1; /* Add bottom border to the last column */
}

/* Ensure proper background for striped rows */
:deep(tr:nth-child(even) td:last-child) {
  background-color: #f1f5f9; /* Match the striped row background */
}

/* Hover state background fix */
:deep(tr:hover td:last-child) {
  background-color: #e5e7eb; /* Match the hover background */
}
</style>