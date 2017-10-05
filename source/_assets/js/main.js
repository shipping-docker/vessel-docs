var hljs = require('highlight.js/lib/highlight.js');
hljs.registerLanguage('php', require('highlight.js/lib/languages/php.js'));
hljs.registerLanguage('bash', require('highlight.js/lib/languages/bash.js'));
hljs.registerLanguage('nginx', require('highlight.js/lib/languages/nginx.js'));
hljs.registerLanguage('yaml', require('highlight.js/lib/languages/yaml.js'));
hljs.registerLanguage('sql', require('highlight.js/lib/languages/sql.js'));
hljs.registerLanguage('json', require('highlight.js/lib/languages/json.js'));
hljs.registerLanguage('apache', require('highlight.js/lib/languages/apache.js'));
hljs.registerLanguage('javascript', require('highlight.js/lib/languages/javascript.js'));

// On page load
document.addEventListener("DOMContentLoaded", function(event) {
    // Highlight.js
    hljs.initHighlightingOnLoad();
});