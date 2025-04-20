<template>
    <div class="relative">
        <!-- Regular Text View -->
        <div class="relative" ref="editorContainer">
            <!-- Display Content -->
            <div
                @click="openEditor"
                class="prose max-w-none min-w-[10rem] p-4 min-h-[5rem] border border-gray-400 rounded-lg bg-white relative overflow-auto cursor-pointer"
            >
                <div v-html="modelValue || ''"></div>
            </div>
        </div>
        <!-- Modal Editor -->
        <div
            v-if="isEditorOpen"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center"
        >
            <div
                class="w-11/12 md:w-4/5 lg:w-3/4 max-h-[90vh] bg-white rounded-lg shadow-xl flex flex-col"
            >
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-medium">{{ title }}</h3>
                    <button
                        @click="closeEditor"
                        class="p-2 hover:bg-gray-100 rounded-full transition-colors tooltipForActions"
                        data-tooltip="Close"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Modal Toolbar -->
                <div class="flex flex-wrap gap-1 p-2 bg-gray-50 border-b">
                    <!-- Text Formatting -->
                    <div class="flex gap-1 mr-2 border-r pr-2">
                        <button
                            @click.stop="
                                editor?.chain().focus().toggleBold().run()
                            "
                            @mousedown.prevent
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="Bold"
                            :class="{ 'bg-gray-200': editor?.isActive('bold') }"
                        >
                            <i class="fas fa-bold"></i>
                        </button>

                        <button
                            @mousedown.prevent
                            @click="
                                editor?.chain().focus().toggleItalic().run()
                            "
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="Italic"
                            :class="{
                                'bg-gray-200': editor?.isActive('italic'),
                            }"
                        >
                            <i class="fas fa-italic"></i>
                        </button>

                        <button
                            @mousedown.prevent
                            @click="
                                editor?.chain().focus().toggleBulletList().run()
                            "
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="List"
                            :class="{
                                'bg-gray-200': editor?.isActive('bulletList'),
                            }"
                        >
                            <i class="fas fa-list-ul"></i>
                        </button>

                        <button
                            @mousedown.prevent
                            @click="addLink"
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="Link"
                            :class="{ 'bg-gray-200': editor?.isActive('link') }"
                        >
                            <i class="fas fa-link"></i>
                        </button>
                    </div>

                    <!-- Color Picker -->
                    <div class="relative mr-2 border-r pr-2">
                        <button
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="Text Color"
                            @mousedown.prevent
                            @click="toggleColorPicker"
                        >
                            <i class="fas fa-palette"></i>
                        </button>

                        <div
                            v-if="showColorPicker"
                            class="absolute top-full w-[12rem] left-0 mt-1 p-2 bg-white border rounded-lg shadow-lg z-50"
                        >
                            <div class="grid grid-cols-4 gap-2 p-2 w-full">
                                <button
                                    v-for="color in colors"
                                    :key="color"
                                    @click="setTextColor(color)"
                                    class="w-8 h-8 rounded-full hover:scale-110 transition-transform tooltipForActions"
                                    :data-tooltip="color"
                                    :style="{ backgroundColor: color }"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Table Controls -->
                    <div class="flex gap-1">
                        <button
                            @click="
                                editor
                                    ?.chain()
                                    .focus()
                                    .insertTable({
                                        rows: 3,
                                        cols: 3,
                                        withHeaderRow: true,
                                    })
                                    .run()
                            "
                            class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                            data-tooltip="Insert Table"
                        >
                            <i class="fas fa-table"></i>
                        </button>

                        <div class="relative flex space-x-2">
                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Add Row Before"
                                @click="
                                    editor?.chain().focus().addRowBefore().run()
                                "
                            >
                                <i class="fas fa-arrow-up"></i>
                            </button>

                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Add Row After"
                                @click="
                                    editor?.chain().focus().addRowAfter().run()
                                "
                            >
                                <i class="fas fa-arrow-down"></i>
                            </button>
                        </div>

                        <div class="relative flex space-x-2">
                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Add Column Before"
                                @click="
                                    editor
                                        ?.chain()
                                        .focus()
                                        .addColumnBefore()
                                        .run()
                                "
                            >
                                <i class="fas fa-arrow-left"></i>
                            </button>

                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Add Column After"
                                @click="
                                    editor
                                        ?.chain()
                                        .focus()
                                        .addColumnAfter()
                                        .run()
                                "
                            >
                                <i class="fas fa-arrow-right"></i>
                            </button>
                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Delete Row"
                                @click="
                                    editor?.chain().focus().deleteRow().run()
                                "
                            >
                                <svg
                                    fill="#000000"
                                    width="20px"
                                    height="20px"
                                    viewBox="0 0 1920 1920"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g
                                        id="SVGRepo_bgCarrier"
                                        stroke-width="0"
                                    ></g>
                                    <g
                                        id="SVGRepo_tracerCarrier"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M225.936 1694.232v-338.88h194.04v-225.96h-194.04v-338.76h194.04v-225.96h-194.04v-338.76h1468.08v338.76h-199.8v225.96h199.8v338.76h-199.8v225.96h199.8v338.88H225.936Zm1694.04-.12v-1468.2c0-124.56-101.28-225.84-225.96-225.84H225.936c-124.68 0-225.96 101.28-225.96 225.84v1468.2c0 124.56 101.28 225.96 225.96 225.96h1468.08c124.68 0 225.96-101.4 225.96-225.96Zm-1201.46-258.94 241.32-241.32 241.2 241.32 233.88-233.76-241.32-241.32 241.32-241.32-233.88-233.76-241.2 241.32-241.32-241.32-233.64 233.76 241.2 241.32-241.2 241.32 233.64 233.76Z"
                                            fill-rule="evenodd"
                                        ></path>
                                    </g>
                                </svg>
                            </button>

                            <button
                                class="p-2 hover:bg-gray-200 rounded transition-colors tooltipForActions"
                                data-tooltip="Delete Column"
                                @click="
                                    editor?.chain().focus().deleteColumn().run()
                                "
                            >
                                <svg
                                    fill="#000000"
                                    width="20px"
                                    height="20px"
                                    viewBox="0 0 1920 1920"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <g
                                        id="SVGRepo_bgCarrier"
                                        stroke-width="0"
                                    ></g>
                                    <g
                                        id="SVGRepo_tracerCarrier"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M1694.232 1694.064h-338.88v-194.04h-225.96v194.04h-338.76v-194.04h-225.96v194.04h-338.76V225.984h338.76v199.8h225.96v-199.8h338.76v199.8h225.96v-199.8h338.88v1468.08Zm-.12-1694.04h-1468.2C101.352.024.072 101.304.072 225.984v1468.08c0 124.68 101.28 225.96 225.84 225.96h1468.2c124.56 0 225.96-101.28 225.96-225.96V225.984c0-124.68-101.4-225.96-225.96-225.96ZM1201.344 484.92l-241.32 241.32-241.2-241.32-233.88 233.76L726.264 960l-241.32 241.32 233.88 233.76 241.2-241.32 241.32 241.32 233.64-233.76-241.2-241.32 241.2-241.32-233.64-233.76Z"
                                            fill-rule="evenodd"
                                        ></path>
                                    </g>
                                </svg>
                            </button>
                        </div>

                        <button
                            @click="editor?.chain().focus().deleteTable().run()"
                            :disabled="!editor?.can().deleteTable()"
                            class="p-2 hover:bg-gray-200 rounded transition-colors disabled:opacity-50 disabled:cursor-not-allowed tooltipForActions"
                            data-tooltip="Delete Table"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <!-- Save & Close -->
                    <!-- <button 
            @mousedown.prevent 
            @click="closeEditor" 
            class="p-2 hover:bg-gray-200 rounded transition-colors ml-auto"
            title="Save & Close"
          >
            <i class="fas fa-save mr-1"></i> Save
          </button> -->
                </div>

                <!-- Modal Content -->
                <div class="p-6 overflow-y-auto flex-grow">
                    <editor-content :editor="editor" class="min-h-[300px]" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { Editor, EditorContent } from "@tiptap/vue-3";
import StarterKit from "@tiptap/starter-kit";
import TextStyle from "@tiptap/extension-text-style";
import Color from "@tiptap/extension-color";
import Link from "@tiptap/extension-link";
import Table from "@tiptap/extension-table";
import TableRow from "@tiptap/extension-table-row";
import TableCell from "@tiptap/extension-table-cell";
import TableHeader from "@tiptap/extension-table-header";
// import { DOMParser } from 'prosemirror-model';

const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },
    title: {
        type: String,
        default: "Text Editor",
    }
});

const emit = defineEmits(["update:modelValue"]);
const editor = ref(null);
const showColorPicker = ref(false);
const editorContainer = ref(null);
const showRowMenu = ref(false);
const showColumnMenu = ref(false);
const isEditorOpen = ref(false);

function openEditor() {
    isEditorOpen.value = true;
    document.body.style.overflow = "hidden";
    setTimeout(() => {
        editor.value?.commands.focus();
    }, 100);
}

function closeEditor() {
    document.body.style.overflow = "auto";
    isEditorOpen.value = false;
}

const toggleColorPicker = () => {
    showColorPicker.value = !showColorPicker.value;
    showRowMenu.value = false;
    showColumnMenu.value = false;
};

const toggleRowMenu = () => {
    showRowMenu.value = !showRowMenu.value;
    showColorPicker.value = false;
    showColumnMenu.value = false;

    if (editor.value) {
        setTimeout(() => {
            editor.value.commands.focus();
        }, 100);
    }
};

const toggleColumnMenu = () => {
    showColumnMenu.value = !showColumnMenu.value;
    showColorPicker.value = false;
    showRowMenu.value = false;
};

const colors = ["#000000", "#4B5563", "#2563EB", "#DC2626"];

const handleClickOutside = (event) => {
    if (
        (showColorPicker.value || showRowMenu.value || showColumnMenu.value) &&
        !event.target.closest("button")
    ) {
        showColorPicker.value = false;
        showRowMenu.value = false;
        showColumnMenu.value = false;
    }
    if (
        showRowMenu.value &&
        editorContainer.value &&
        !editorContainer.value.contains(event.target)
    ) {
        showRowMenu.value = false;
    }
    if (
        showColumnMenu.value &&
        editorContainer.value &&
        !editorContainer.value.contains(event.target)
    ) {
        showColumnMenu.value = false;
    }
};

const addLink = () => {
    const isActive = editor.value.isActive("link");

    if (isActive) {
        editor.value.chain().focus().unsetLink().run();
        return;
    }

    const url = window.prompt("URL");
    if (url) {
        const absoluteUrl = url.startsWith("http") ? url : `https://${url}`;
        editor.value
            .chain()
            .focus()
            .setLink({ href: absoluteUrl, target: "_blank" })
            .run();
    }
};

const setTextColor = (color) => {
    editor.value.chain().focus().setColor(color).run();
    showColorPicker.value = false;
};

// Initialize editor when component is mounted
onMounted(() => {
    editor.value = new Editor({
        content: props.modelValue,
        extensions: [
            StarterKit.configure({
                bulletList: true,
            }),
            TextStyle,
            Color,
            Link.configure({
                openOnClick: false,
                HTMLAttributes: {
                    target: "_blank",
                    rel: "noopener noreferrer",
                    class: "text-blue-600 underline",
                },
                linkOnPaste: true,
            }),
            Table.configure({
                resizable: true,
                allowTableNodeSelection: true,
            }),
            TableRow,
            TableHeader,
            TableCell,
        ],
        onUpdate: ({ editor }) => {
            emit("update:modelValue", editor.getHTML());
        },
        editorProps: {
        handlePaste: (view, event, slice) => {
            // Get the clipboard content as HTML
            const html = event.clipboardData.getData('text/html');
            
            // If HTML content is available, use it (with sanitization)
            if (html) {
                // Prevent default paste behavior
                event.preventDefault();
                
                // Create a temporary div to parse and sanitize the HTML
                const div = document.createElement('div');
                div.innerHTML = html;
                
                // Remove any script tags or other potentially harmful elements
                const scripts = div.querySelectorAll('script');
                scripts.forEach(script => script.remove());
                
                // Use the editor's insertContent method to insert HTML
                editor.value.commands.insertContent(div.innerHTML);
                
                return true;
            }
            
            // If no HTML, let TipTap handle the default paste behavior
            return false;
        }
    }
    });

    document.addEventListener("mousedown", handleClickOutside);

    // Handle escape key to exit editor modal
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            handleClickOutside();
        }
    });
});



// Watch for external changes to modelValue
watch(
    () => props.modelValue,
    (newContent) => {
        if (editor.value && newContent !== editor.value.getHTML()) {
            editor.value.commands.setContent(newContent);
        }
    },
    { immediate: true }
);

onBeforeUnmount(() => {
    document.removeEventListener("mousedown", handleClickOutside);
    editor.value?.destroy();
});
</script>

<style scoped>
/* Minimal required styles that can't be done with Tailwind */
.ProseMirror {
    min-height: 10rem;
    outline: none;
}

.ProseMirror table {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
    margin: 0;
}

.ProseMirror td,
.ProseMirror th {
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
    position: relative;
}

.ProseMirror th {
    font-weight: bold;
    background-color: #f9fafb;
}

.ProseMirror ul {
    list-style-type: disc;
    padding-left: 1.5rem;
}

.ProseMirror ul li {
    margin-bottom: 0.25rem;
}

/* Placeholder text */
.ProseMirror p.is-editor-empty:first-child::before {
    content: "Start typing...";
    float: left;
    color: #9ca3af;
    pointer-events: none;
    height: 0;
}

/* Style for the display view */
.prose ul {
    list-style-type: disc;
    padding-left: 1.5rem;
}

.prose table {
    border-collapse: collapse;
    width: 100%;
    margin: 1rem 0;
}

.prose td,
.prose th {
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
}

.prose th {
    font-weight: bold;
    background-color: #f9fafb;
}
</style>
