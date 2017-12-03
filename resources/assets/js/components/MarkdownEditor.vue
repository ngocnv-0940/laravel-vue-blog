<template>
  <div class="markdown-editor">
    <textarea></textarea>
    <b-modal :active="showUpload" @close="closeUpload" scroll="clip">
      <form action="">
          <div class="modal-card">
              <header class="modal-card-head">
                  <p class="modal-card-title">Login</p>
              </header>
              <section class="modal-card-body has-text-centered">
                <b-field>
                    <b-upload v-model="dropFiles"
                        multiple
                        drag-drop>
                        <section class="section">
                            <div class="content has-text-centered">
                                <p>
                                    <b-icon
                                        icon="upload"
                                        size="is-large">
                                    </b-icon>
                                </p>
                                <p>Drop your files here or click to upload</p>
                            </div>
                        </section>
                    </b-upload>
                </b-field>

                <div class="tags">
                    <span v-for="(file, index) in dropFiles"
                        :key="index"
                        class="tag is-primary" >
                        {{ file.name }}
                        <button class="delete is-small"
                            type="button"
                            @click="deleteDropFile(index)">
                        </button>
                    </span>
                </div>
              </section>
              <footer class="modal-card-foot">
                  <button class="button" type="button" @click="closeUpload">Close</button>
                  <button class="button is-primary" @click.prevent="emitUpload">Login</button>
              </footer>
          </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import SimpleMDE from 'simplemde';
import marked from 'marked';

export default {
  data() {
    return {
      dropFiles: []
    }
  },
  name: 'markdown-editor',
  props: {
    value: String,
    previewClass: String,
    autoinit: {
      type: Boolean,
      default() {
        return true;
      },
    },
    highlight: {
      type: Boolean,
      default() {
        return false;
      },
    },
    sanitize: {
      type: Boolean,
      default() {
        return false;
      },
    },
    configs: {
      type: Object,
      default() {
        return {};
      },
    },
    showUpload: Boolean
  },
  mounted() {
    if (this.autoinit) this.initialize();
  },
  activated() {
    const editor = this.simplemde;
    if (!editor) return;
    const isActive = editor.isSideBySideActive() || editor.isPreviewActive();
    if (isActive) editor.toggleFullScreen();
  },
  methods: {
    initialize() {
      const configs = {
        element: this.$el.firstElementChild,
        initialValue: this.value,
        renderingConfig: {},
        status: ["lines", "words", "cursor"],
        autoDownloadFontAwesome: false,
        spellChecker: false,
        toolbar: [
          "bold", "italic", "heading", 'strikethrough', "|",
          "code", "quote", 'unordered-list', 'ordered-list', '|',
          {
            name: "upload",
            action: (editor) => {
              this.$emit('update:showUpload', true)
              console.log('OK')
              return
              var cm = editor.codemirror;
              var output = '';
              var selectedText = cm.getSelection();
              var text = selectedText || 'placeholder';

              output = '!!' + text + '!!';
              cm.replaceSelection(output);
            },
            className: "fa fa-image",
            title: "Upload ảnh"
          },
          {
            name: 'insertUrl',
            action: (editor) => {
              this.$dialog.prompt({
                  message: 'Nhập URL để chèn',
                  inputAttrs: {
                      placeholder: 'http://blaysku.com',
                      type: 'url'
                  },
                  onConfirm: (value) => {
                    var cm = editor.codemirror;
                    var output = '[](' + value + ')';
                    var selectedText = cm.getSelection();
                    var text = selectedText || 'placeholder';
                    output = '[' + text + '](' + value + ')';
                    cm.replaceSelection(output);
                  }
              })
            },
            className: 'fa fa-link',
            title: 'Chèn URL'
          },
          'table', 'horizontal-rule', '|',
          'preview', 'side-by-side', 'fullscreen'
        ],
      };
      Object.assign(configs, this.configs);
      // 判断是否开启代码高亮
      if (this.highlight) {
        configs.renderingConfig.codeSyntaxHighlighting = true;
      }

      // 设置是否渲染输入的html
      marked.setOptions({ sanitize: this.sanitize });

      // 实例化编辑器
      this.simplemde = new SimpleMDE(configs);

      // 添加自定义 previewClass
      const className = this.previewClass || '';
      this.addPreviewClass(className);

      // 绑定事件
      this.bindingEvents();
    },
    closeUpload() {
      this.$emit('update:showUpload', false)
    },
    deleteDropFile(index) {
        this.dropFiles.splice(index, 1)
    },
    bindingEvents() {
      this.simplemde.codemirror.on('change', () => {
        this.$emit('input', this.simplemde.value());
      });
    },
    addPreviewClass(className) {
      const wrapper = this.simplemde.codemirror.getWrapperElement();
      const preview = document.createElement('div');
      wrapper.nextSibling.className += ` ${className}`;
      preview.className = `editor-preview ${className}`;
      wrapper.appendChild(preview);
    },
  },
  destroyed() {
    this.simplemde = null;
  },
  watch: {
    value(val) {
      if (val === this.simplemde.value()) return;
      this.simplemde.value(val);
    },
  },
};
</script>

<style>
.markdown-editor .markdown-body {
  padding: 0.5em
}

.markdown-editor .editor-preview-active, .markdown-editor .editor-preview-active-side {
  display: block;
}

.editor-toolbar.fullscreen {
  z-index: 99;
}
</style>
