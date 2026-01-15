import './bootstrap';
// Ensure NProgress is exposed in a bundler-agnostic way so Livewire can call .configure()
import * as NProgressModule from 'nprogress';
import 'nprogress/nprogress.css';

// Prefer default export if present, otherwise use the module directly
const NProgress = NProgressModule.default ?? NProgressModule;
window.NProgress = NProgress;

import hljs from 'highlight.js/lib/core';
import xml from 'highlight.js/lib/languages/xml';
import 'highlight.js/styles/github-dark.css';

hljs.registerLanguage('xml', xml);

window.hljs = hljs;

// Workbench module (handles placeholders, Monaco, drafts)
import './workbench';
