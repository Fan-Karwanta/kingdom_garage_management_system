// quotation-editor.js
// Simple Quill editor initialization for single editor pages (like quotation settings)

(function() {
  'use strict';

  let quillInstance = null;

  function initializeQuillEditor() {
    // Find the editor element
    const editorElement = document.querySelector('.quill-editor');
    
    if (!editorElement) {
      console.error('Quill editor element not found');
      return;
    }

    const editorId = editorElement.id;
    
    // Check if Quill is loaded
    if (typeof Quill === 'undefined') {
      console.error('Quill library not loaded');
      return;
    }

    try {
      // Get initial content from textarea
      const initialContent = editorElement.value || editorElement.textContent || '';
      
      // Get the parent container
      const parentContainer = editorElement.parentNode;
      
      // Create a wrapper div
      const wrapperDiv = document.createElement('div');
      wrapperDiv.style.position = 'relative';
      wrapperDiv.style.marginBottom = '40px';
      wrapperDiv.style.display = 'block';
      wrapperDiv.style.clear = 'both';
      
      // Create a container for Quill
      const quillContainer = document.createElement('div');
      quillContainer.id = 'quill-container-' + editorId;
      quillContainer.className = 'quill-editor-container';
      
      // Append quill container to wrapper
      wrapperDiv.appendChild(quillContainer);
      
      // Insert the wrapper after the textarea
      parentContainer.insertBefore(wrapperDiv, editorElement.nextSibling);
      
      // Hide the original textarea but keep it in the DOM
      editorElement.style.display = 'none';
      editorElement.style.visibility = 'hidden';
      editorElement.style.position = 'absolute';
      editorElement.style.left = '-9999px';
      
      // Initialize Quill
      quillInstance = new Quill('#' + quillContainer.id, {
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
        },
        placeholder: 'Enter terms and conditions here...'
      });

      // Set initial content
      if (initialContent) {
        quillInstance.root.innerHTML = initialContent;
      }

      // Update textarea on content change
      quillInstance.on('text-change', function() {
        editorElement.value = quillInstance.root.innerHTML;
      });

      // Update textarea before form submission
      const form = editorElement.closest('form');
      if (form) {
        form.addEventListener('submit', function() {
          editorElement.value = quillInstance.root.innerHTML;
        });
      }

      console.log('Quill editor initialized successfully');
    } catch (error) {
      console.error('Error initializing Quill editor:', error);
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      setTimeout(initializeQuillEditor, 100);
    });
  } else {
    setTimeout(initializeQuillEditor, 100);
  }

  // Expose to global scope if needed
  window.getQuillInstance = function() {
    return quillInstance;
  };
})();