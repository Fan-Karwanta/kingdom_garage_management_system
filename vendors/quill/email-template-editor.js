// email-template-editor.js
// CSP-safe initialization for Quill editors

document.addEventListener('DOMContentLoaded', function() {
  // Check if emailTemplateData is available
  if (typeof window.emailTemplateData === 'undefined') {
    console.error('Email template data not found');
    return;
  }

  const editors = {};

  // Initialize Quill for each email template
  window.emailTemplateData.forEach(function(template) {
    const editorId = 'editor_' + template.id;
    const hiddenTextareaId = 'hidden_' + editorId;
    
    const editorContainer = document.getElementById(editorId);
    const hiddenTextarea = document.getElementById(hiddenTextareaId);
    
    if (!editorContainer || !hiddenTextarea) {
      console.error('Editor container or textarea not found for ID:', template.id);
      return;
    }

    // Initialize Quill
    const quill = new Quill('#' + editorId, {
      theme: 'snow',
      modules: {
        toolbar: [
          ['bold', 'italic', 'underline', 'strike'],
          ['blockquote', 'code-block'],
          [{ 'header': 1 }, { 'header': 2 }],
          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'script': 'sub'}, { 'script': 'super' }],
          [{ 'indent': '-1'}, { 'indent': '+1' }],
          [{ 'size': ['small', false, 'large', 'huge'] }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
          [{ 'color': [] }, { 'background': [] }],
          [{ 'font': [] }],
          [{ 'align': [] }],
          ['clean']
        ]
      }
    });

    // Set initial content
    quill.root.innerHTML = template.content;
    hiddenTextarea.value = template.content;

    // Store editor instance
    editors[template.id] = quill;

    // Update hidden textarea when content changes
    quill.on('text-change', function() {
      hiddenTextarea.value = quill.root.innerHTML;
    });
  });

  // Handle form submissions
  document.querySelectorAll('form[name="parent_form"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
      // Ensure all hidden textareas are updated before submission
      Object.keys(editors).forEach(function(id) {
        const hiddenTextarea = document.getElementById('hidden_editor_' + id);
        if (hiddenTextarea && editors[id]) {
          hiddenTextarea.value = editors[id].root.innerHTML;
        }
      });
    });
  });
});