<template>
  <div class="markdown-editor">
    <textarea></textarea>
    <b-modal :active.sync="showUpload">
      <form action="">
        <div class="modal-card">
          <header class="modal-card-head">
            <p class="modal-card-title">Upload ảnh</p>
          </header>
          <section class="modal-card-body has-text-centered">
            <b-field>
              <b-upload v-model="dropFiles"
                accept="image/*"
                :loading="uploading"
                multiple drag-drop required>
                <section class="section">
                  <div class="content has-text-centered">
                    <p><b-icon icon="upload" size="is-large"></b-icon></p>
                    <p>Kéo và thả file vào đây hoặc click để upload</p>
                  </div>
                </section>
              </b-upload>
            </b-field>
            <div class="tags">
              <span v-for="(file, index) in dropFiles" :key="index" class="tag is-primary">
                  {{ file.name }}
                  <button class="delete is-small"
                    type="button"
                    @click="deleteDropFile(index)">
                  </button>
                </span>
            </div>
            <section>
              <div class="columns is-multiline is-mobile">
                <div class="column is-2" v-for="image in images" :key="image.id">
                  <img :src="image.thumb" class="image-thumb" alt="image" @click="selectImage(image.url)">
                </div>
              </div>
            </section>
            <section>
              <b-pagination
                @change="fetchMedia"
                :total="total"
                :current.sync="current_page"
                :simple="true"
                :per-page="per_page">
              </b-pagination>
            </section>
          </section>
          <footer class="modal-card-foot">
            <button class="button" type="button" @click="showUpload = false">Hủy</button>
            <button class="button" type="button" v-show="dropFiles.length" @click="dropFiles = []">Xóa ảnh đã chọn</button>
            <button class="button is-primary" :disabled="!dropFiles.length" @click.prevent="upload">Tải lên</button>
          </footer>
        </div>
      </form>
    </b-modal>
  </div>
</template>

<script>
import SimpleMDE from 'simplemde'
import marked from 'marked'
import axios from 'axios'

export default {
  data() {
    return {
      dropFiles: [],
      showUpload: false,
      uploading: false,
      images: [],
      current_page: 1,
      per_page: 0,
      total: 0
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
    }
  },

  created() {
    this.fetchMedia(this.current_page)
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
    async fetchMedia(page) {
      let { data: { data, meta: { total, per_page, current_page }}} = await axios.get(route('user.media', this.$store.getters.authUser.username), {
        params: { page: page }
      })
      this.current_page = current_page
      this.per_page = per_page
      this.images = data
      this.total = total
    },
    async upload() {
      try {
        this.uploading = true
        let formData = new FormData()
        this.dropFiles.forEach(file => formData.append('uploads[]', file))
        let { data } = await axios.post(route('user.upload'), formData)
        this.dropFiles = []
        this.fetchMedia()
      } finally {
        this.uploading = false
      }
    },
    selectImage(image) {
      const editor = this.simplemde
      var cm = editor.codemirror;
      let options = ['![', '](#url#)']
      this._replaceSelection(cm, null, options, image)
      this.showUpload = false
    },
    _replaceSelection(cm, active, startEnd, url) {
      if(/editor-preview-active/.test(cm.getWrapperElement().lastChild.className))
        return;

      var text;
      var start = startEnd[0];
      var end = startEnd[1];
      var startPoint = cm.getCursor("start");
      var endPoint = cm.getCursor("end");
      if(url) {
        end = end.replace("#url#", url);
      }
      if(active) {
        text = cm.getLine(startPoint.line);
        start = text.slice(0, startPoint.ch);
        end = text.slice(startPoint.ch);
        cm.replaceRange(start + end, {
          line: startPoint.line,
          ch: 0
        });
      } else {
        text = cm.getSelection();
        cm.replaceSelection(start + text + end);

        startPoint.ch += start.length;
        if(startPoint !== endPoint) {
          endPoint.ch += start.length;
        }
      }
      cm.setSelection(startPoint, endPoint);
      cm.focus();
    },
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
              this.showUpload = true
            },
            className: "fa fa-image",
            title: "Upload ảnh"
          },
          {
            name: 'insertUrl',
            action: (editor) => {
              this.$dialog.prompt({
                message: 'Nhập URL để chèn',
                confirmText: 'OK',
                cancelText: 'Hủy',
                inputAttrs: {
                  placeholder: 'http://nguyenvanngoc.com',
                  type: 'url'
                },
                onConfirm: (value) => {
                  let cm = editor.codemirror
                  let options = editor.options
                  this._replaceSelection(cm, null, options.insertTexts.link, value)
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
      if (this.highlight) {
        configs.renderingConfig.codeSyntaxHighlighting = true;
      }

      marked.setOptions({ sanitize: this.sanitize });

      this.simplemde = new SimpleMDE(configs);

      const className = this.previewClass || '';
      this.addPreviewClass(className);

      this.bindingEvents();
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

.image-thumb {
  max-width: 80px;
  cursor: pointer;
}
.image-thumb:hover {
  opacity: 0.8;
}
</style>
