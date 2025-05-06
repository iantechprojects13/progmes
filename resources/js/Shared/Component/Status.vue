<template>
    <div
        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium whitespace-nowrap select-none"
        :class="statusClass"
    >
        {{ displayText }}
    </div>
</template>

<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    text: {
        type: [String, Number, null],
        required: true,
    },
});

const statusConfig = computed(() => {
    const isHEI = usePage().props.auth.user.type === "HEI";

    return {
        blue: [
            "Submitted",
            "Monitored",
            "Applied",
            "Complied",
            "For re-evaluation",
            "Completed",
            "Yes",
        ],
        green: [1, "In progress", "Deployed", "DeployedforHEI"],
        red: [0, "Locked", "Not complied", "No"],
        gray: ["draft", "Not applicable", "No status", null, ""],
        textMap: {
            Submitted: "Completed",
            Completed: "Completed",
            Applied: "Applied",
            draft: "Draft",
            "In progress": "In progress",
            Locked: "Locked",
            "For re-evaluation": "For re-evaluation",
            Monitored: "Monitored",
            1: "Active",
            0: "Inactive",
            Yes: "Yes",
            No: "No",
            "No status": "No status",
            Complied: "Complied",
            "Not complied": "Not complied",
            "Not applicable": "Not applicable",
            Deployed: isHEI ? "Available" : "Deployed",
            "": "No status",
            null: "No status",
        },
    };
});

const statusClass = computed(() => {
    const config = statusConfig.value;
    const text = props.text;

    if (config.blue.includes(text)) {
        return "bg-blue-800 text-blue-100 border border-blue-600";
    } else if (config.green.includes(text)) {
        return "bg-green-800 text-green-100 border border-green-600";
    } else if (config.red.includes(text)) {
        return "bg-red-800 text-red-100 border border-red-600";
    } else {
        return "bg-gray-800 text-gray-100 border border-gray-600";
    }
});

const displayText = computed(() => {
    return statusConfig.value.textMap[props.text] || "No status";
});
</script>
