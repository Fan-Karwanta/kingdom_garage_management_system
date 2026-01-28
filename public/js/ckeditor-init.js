// ckeditor-init.js
// External initialization for CKEditor to comply with CSP

document.addEventListener('DOMContentLoaded', function() {
  // Check if CKEditor data is available
  if (typeof window.ckeditorConfigs === 'undefined') {
    console.error('CKEditor configuration data not found');
    return;
  }

  // Common toolbar configuration
  const toolbarConfig = {
    toolbar: [
      {
        name: 'styles',
        items: ['Bold', 'Italic']
      },
      {
        name: 'basicstyles',
        items: ['Underline', 'Subscript', 'Superscript', 'RemoveFormat']
      },
      {
        name: 'paragraph',
        items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv']
      },
      {
        name: 'undo',
        items: ['Undo', 'Redo']
      },
      {
        name: 'styles',
        items: ['Format', 'Font', 'FontSize']
      },
      {
        name: 'document',
        items: ['Source']
      }
    ],
    format_tags: 'p;h1;h2;h3;h4;h5;h6'
  };

  // Initialize each CKEditor instance
  window.ckeditorConfigs.forEach(function(config) {
    const editorElement = document.getElementById(config.editorId);
    
    if (editorElement && typeof CKEDITOR !== 'undefined') {
      CKEDITOR.replace(config.editorId, toolbarConfig);
    } else {
      console.error('CKEditor element not found or CKEDITOR not loaded:', config.editorId);
    }
  });
});