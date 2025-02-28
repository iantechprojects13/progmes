<template>
  <div class="flex flex-col">
    <div class="flex flex-wrap p-1 bg-white rounded-t-lg border border-b-10 border-gray-200">
      <button 
        @click="editor?.chain().focus().toggleBold().run()"
        :class="['p-2 hover:bg-gray-100 rounded transition-colors', { 'bg-gray-100': editor?.isActive('bold') }]"
      >
        <i class="fas fa-bold"></i>
      </button>

      <!-- Italic -->
      <button 
        @click="editor?.chain().focus().toggleItalic().run()"
        :class="['p-2 hover:bg-gray-100 rounded transition-colors', { 'bg-gray-100': editor?.isActive('italic') }]"
      >
        <i class="fas fa-italic"></i>
      </button>

      <!-- Bullet List -->
      <button 
        @click="editor?.chain().focus().toggleBulletList().run()"
        :class="['p-2 hover:bg-gray-100 rounded transition-colors', { 'bg-gray-100': editor?.isActive('bulletList') }]"
      >
        <i class="fas fa-list-ul"></i>
      </button>

      <button 
        @click="addLink"
        :class="['p-2 hover:bg-gray-100 rounded transition-colors', { 'bg-gray-100': editor?.isActive('link') }]"
      >
        <i class="fas fa-link"></i>
      </button>

      <!-- Color Picker -->
      <div class="relative">
        <button 
          class="p-2 hover:bg-gray-100 rounded transition-colors"
          @click="showColorPicker = !showColorPicker"
        >
          <i class="fas fa-palette"></i>
        </button>
        
        <div v-if="showColorPicker" class="absolute top-full left-0 mt-1 p-3 bg-white border rounded-lg shadow-lg z-50">
          <div class="grid grid-cols-5 gap-2">
            <button 
              v-for="color in colors" 
              :key="color"
              @click="setTextColor(color)"
              class="w-8 h-8 rounded hover:scale-110 transition-transform"
              :style="{ backgroundColor: color }"
            />
          </div>
        </div>
        <div v-if="showColorPicker" class="absolute top-full w-52 left-0 mt-1 p-3 bg-white border rounded-lg shadow-lg z-50">
          <div class="grid grid-cols-5 gap-2">
            <button 
              v-for="color in colors" 
              :key="color"
              @click="setTextColor(color)"
              class="w-8 h-8 rounded-full hover:scale-110 transition-transform"
              :style="{ backgroundColor: color }"
            />
          </div>
        </div>
      </div>
    </div>

    <div 
      class="prose max-w-none p-4 min-h-[200px] border border-gray-200 rounded-b-lg bg-white focus:outline-none"
      contenteditable="false"
    >
      <editor-content :editor="editor" />
    </div>
  </div>
</template>

<script>

</script>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount, watch } from 'vue'
  import { Editor, EditorContent } from '@tiptap/vue-3'
  import StarterKit from '@tiptap/starter-kit'
  import TextStyle from '@tiptap/extension-text-style'
  import Color from '@tiptap/extension-color'
  import Link from '@tiptap/extension-link'
  
  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  })
  
  const emit = defineEmits(['update:modelValue'])
  const editor = ref(null)
  const showColorPicker = ref(false)
  
  const colors = [
    '#000000', '#FF0000', '#00FF00', '#0000FF', '#FFFF00',
  ]
  
  const addLink = () => {
    const isActive = editor.value.isActive('link')
    
    if (isActive) {
      editor.value.chain().focus().unsetLink().run()
      return
    }

    const url = window.prompt('URL')
    if (url) {
      const absoluteUrl = url.startsWith('http') ? url : `https://${url}`
      editor.value.chain().focus().setLink({ href: absoluteUrl, target: '_blank' }).run()
    }
  }
  
  
  
  const setTextColor = (color) => {
    editor.value.chain().focus().setColor(color).run()
    showColorPicker.value = false
  }
  
  watch(() => props.modelValue, (newContent) => {
    if (editor.value && newContent !== editor.value.getHTML()) {
      editor.value.commands.setContent(newContent)
    }
  }, { immediate: true })

  onMounted(() => {
  editor.value = new Editor({
    content: props.modelValue,
    extensions: [
      StarterKit.configure({
        bulletList: true,
        placeholder: {
          placeholder: 'Start writing...'
        }
      }),
      TextStyle,
      Color,
      Link.configure({
        openOnClick: false,
        HTMLAttributes: {
          target: '_blank',
          rel: 'noopener noreferrer'
        },
        linkOnPaste: true,
      })
    ],
    onUpdate: ({ editor }) => {
      emit('update:modelValue', editor.getHTML())
    }
  })
})
  
  onBeforeUnmount(() => {
    editor.value?.destroy()
  })
  </script>
  
  <style>

  .ProseMirror {
    min-height: 200px;
    cursor: text;
  }


  .ProseMirror:focus {
    outline: none;
  }
  
  
  .ProseMirror a {
    color: #2563eb;
    text-decoration: underline;
    cursor: pointer;
  }

  
  
  /* Optional: Add hover effect */
  .ProseMirror a:hover {
    color: #1d4ed8; /* Darker blue on hover */
  }



  .ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
  }
  
  .ProseMirror ul {
    list-style-type: disc;
    padding-left: 1.5em;
  }
  
  .ProseMirror ul li {
    margin-bottom: 0.5em;
  }
  </style>