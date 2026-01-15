# AI Web Design Workbench - CMS Integration Guide

**Generated:** January 15, 2026  
**Purpose:** Design system reference for ai-web-design-workbench project  
**Status:** Temporary reference file - DO NOT COMMIT

---

## Quick Overview
This guide provides the design system, Monaco editor setup, and split-screen layout patterns from your ClientBridge CMS to replicate in your new workbench tool.

---

## 🎨 Design System

### Color Palette (Tailwind v3)
```javascript
// Primary Colors
primary: {
  50: '#eff6ff',  100: '#dbeafe',  200: '#bfdbfe',
  300: '#93c5fd', 400: '#60a5fa',  500: '#3b82f6',
  600: '#2563eb', 700: '#1d4ed8',  800: '#1e40af',
  900: '#1e3a8a'
}

// Secondary Colors
secondary: {
  50: '#faf5ff',  100: '#f3e8ff',  200: '#e9d5ff',
  300: '#d8b4fe', 400: '#c084fc',  500: '#a855f7',
  600: '#8b5cf6', 700: '#7c3aed',  800: '#6d28d9',
  900: '#5b21b6'
}

// Extended Grays
gray: {
  850: '#1a222e',
  950: '#0a0e16'
}
```

### Component Standards

#### Cards
```html
<div class="bg-gray-800/60 rounded-xl border border-gray-700/50 p-6 shadow-xl">
  <!-- Content -->
</div>
```

#### Buttons
```html
<!-- Primary -->
<button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors shadow-sm">
  Primary Action
</button>

<!-- Secondary -->
<button class="bg-gray-700 hover:bg-gray-600 text-white font-medium px-4 py-2 rounded-lg transition-colors">
  Secondary
</button>

<!-- Accent (Theme-aware) -->
<button class="bg-gradient-to-br from-orange-600 to-orange-700 text-white font-semibold px-6 py-3 rounded-xl shadow-xl hover:shadow-2xl transition-all">
  Accent Action
</button>
```

#### Dark Mode Base
```css
/* Default dark background */
body {
  background: #111827; /* gray-900 */
  color: #f9fafb; /* gray-50 */
}

/* Card backgrounds */
.card-bg {
  background: rgba(31, 41, 55, 0.6); /* gray-800/60 */
}

/* Border colors */
.border-default {
  border-color: rgba(55, 65, 81, 0.5); /* gray-700/50 */
}
```

---

## 💻 Monaco Editor (VSCode Style)

### Package Dependencies
```json
{
  "dependencies": {
    "@monaco-editor/react": "^4.7.0",
    "monaco-editor": "^0.55.1"
  }
}
```

### React Component Implementation
```jsx
// MonacoEditor.jsx
import React, { useRef, useEffect } from 'react';
import Editor from '@monaco-editor/react';

export default function MonacoEditor({ 
    value, 
    onChange, 
    language = 'html',  // 'html', 'css', 'javascript'
    height = '400px',
    theme = 'vs-dark',
    readOnly = false 
}) {
    const editorRef = useRef(null);

    function handleEditorDidMount(editor, monaco) {
        editorRef.current = editor;
        
        // Configure editor options
        editor.updateOptions({
            minimap: { enabled: false },
            fontSize: 14,
            lineNumbers: 'on',
            roundedSelection: true,
            scrollBeyondLastLine: false,
            readOnly: readOnly,
            automaticLayout: true,
            tabSize: 2,
            wordWrap: 'on',
        });
    }

    function handleEditorChange(value) {
        if (onChange) {
            onChange(value);
        }
    }

    return (
        <div className="border border-gray-700 rounded-lg overflow-hidden">
            <Editor
                height={height}
                language={language}
                value={value}
                theme={theme}
                onChange={handleEditorChange}
                onMount={handleEditorDidMount}
                options={{
                    readOnly: readOnly,
                    minimap: { enabled: false },
                }}
            />
        </div>
    );
}
```

### Usage Example
```jsx
import MonacoEditor from './components/MonacoEditor';

function PageBuilder() {
    const [html, setHtml] = useState('<div class="hero">Hello World</div>');
    const [css, setCSS] = useState('.hero { padding: 2rem; }');
    
    return (
        <div>
            <MonacoEditor 
                value={html}
                onChange={setHtml}
                language="html"
                height="500px"
            />
            
            <MonacoEditor 
                value={css}
                onChange={setCSS}
                language="css"
                height="300px"
            />
        </div>
    );
}
```

---

## 📐 Split-Screen Layout Pattern

### Bottom-Right Editor + Right Preview Layout
```jsx
function WorkbenchLayout() {
    const [activeTab, setActiveTab] = useState('html');
    const [htmlCode, setHtmlCode] = useState('');
    const [cssCode, setCssCode] = useState('');
    
    const generatedHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <script src="https://cdn.tailwindcss.com"></script>
            <style>${cssCode}</style>
        </head>
        <body>${htmlCode}</body>
        </html>
    `;
    
    return (
        <div className="h-screen flex flex-col bg-gray-900">
            {/* Header */}
            <header className="bg-gray-800 border-b border-gray-700 p-4">
                <h1 className="text-xl font-bold text-white">AI Web Design Workbench</h1>
            </header>
            
            {/* Main Content Area */}
            <div className="flex-1 flex overflow-hidden">
                {/* Left Side - Preview */}
                <div className="w-1/2 border-r border-gray-700 overflow-auto bg-white">
                    <iframe 
                        srcDoc={generatedHTML} 
                        className="w-full h-full border-0"
                        title="Preview"
                    />
                </div>
                
                {/* Right Side - Editor */}
                <div className="w-1/2 flex flex-col">
                    {/* Editor Tabs */}
                    <div className="bg-gray-800 border-b border-gray-700 flex">
                        <button 
                            onClick={() => setActiveTab('html')}
                            className={`px-4 py-3 font-medium transition-colors ${
                                activeTab === 'html' 
                                    ? 'bg-gray-900 text-blue-400 border-b-2 border-blue-500' 
                                    : 'text-gray-400 hover:text-gray-200'
                            }`}
                        >
                            <i className="fas fa-code mr-2"></i>HTML
                        </button>
                        <button 
                            onClick={() => setActiveTab('css')}
                            className={`px-4 py-3 font-medium transition-colors ${
                                activeTab === 'css' 
                                    ? 'bg-gray-900 text-blue-400 border-b-2 border-blue-500' 
                                    : 'text-gray-400 hover:text-gray-200'
                            }`}
                        >
                            <i className="fab fa-css3-alt mr-2"></i>CSS
                        </button>
                    </div>
                    
                    {/* Monaco Editor */}
                    <div className="flex-1 bg-gray-900">
                        {activeTab === 'html' && (
                            <MonacoEditor 
                                value={htmlCode}
                                onChange={setHtmlCode}
                                language="html"
                                height="100%"
                            />
                        )}
                        {activeTab === 'css' && (
                            <MonacoEditor 
                                value={cssCode}
                                onChange={setCssCode}
                                language="css"
                                height="100%"
                            />
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}
```

### Alternative: Bottom Editor Layout
```jsx
function BottomEditorLayout() {
    const [code, setCode] = useState('');
    
    const generatedHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body>${code}</body>
        </html>
    `;
    
    return (
        <div className="h-screen flex flex-col bg-gray-900">
            {/* Top - Preview (60% height) */}
            <div className="h-3/5 bg-white overflow-auto border-b-2 border-gray-700">
                <iframe 
                    srcDoc={generatedHTML} 
                    className="w-full h-full border-0"
                    title="Preview" 
                />
            </div>
            
            {/* Bottom - Editor (40% height) */}
            <div className="h-2/5 bg-gray-900">
                <MonacoEditor 
                    value={code}
                    onChange={setCode}
                    language="html"
                    height="100%"
                />
            </div>
        </div>
    );
}
```

---

## 🎯 Opinionated Design Principles from CMS

### 1. Semantic HTML Classes (AI-Friendly)
```css
/* Define semantic classes, not utility-heavy */
.hero {
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
    padding: 4rem 2rem;
    text-align: center;
    color: white;
}

.section {
    padding: 3rem 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.cta-button {
    background: var(--brand-accent);
    color: white;
    padding: 1rem 2rem;
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s;
}

.cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
}

.feature-card {
    background: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.feature-card:hover {
    transform: translateY(-4px);
}
```

### 2. CSS Variable System
```css
:root {
    /* Brand Colors */
    --brand-primary: #3b82f6;
    --brand-secondary: #8b5cf6;
    --brand-accent: #f97316;
    --brand-text: #1f2937;
    --brand-navbar: #1f2937;
    
    /* Semantic Colors */
    --color-success: #10b981;
    --color-warning: #f59e0b;
    --color-error: #ef4444;
    
    /* Typography */
    --font-base: 'Figtree', sans-serif;
    --font-heading: 'Figtree', sans-serif;
}

/* Usage */
.my-element {
    background-color: var(--brand-primary);
    color: var(--brand-text);
}
```

### 3. Tailwind + Custom CSS Hybrid
```html
<!-- Mix Tailwind utilities with semantic classes -->
<div class="container mx-auto px-4">
    <section class="hero">
        <h1 class="text-5xl font-bold mb-6">Welcome</h1>
        <p class="text-xl mb-8">Your journey starts here</p>
        <a href="#" class="cta-button">Get Started</a>
    </section>
    
    <div class="feature-grid">
        <div class="feature-card">
            <i class="fas fa-rocket text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Fast</h3>
            <p class="text-gray-600">Lightning quick performance</p>
        </div>
        <!-- More cards -->
    </div>
</div>
```

---

## 🔧 Tailwind Configuration

### Full Safelist (For Dynamic Classes)
```javascript
// tailwind.config.js
export default {
  darkMode: 'class',
  content: [
    "./index.html",
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  safelist: [
    // Background colors - all palette colors
    {
      pattern: /^bg-(slate|gray|zinc|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900)$/
    },
    // Text colors
    {
      pattern: /^text-(slate|gray|zinc|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900)$/
    },
    // Border colors
    {
      pattern: /^border-(slate|gray|zinc|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900)$/
    },
    // Spacing
    {
      pattern: /^(p|px|py|pt|pb|pl|pr|m|mx|my|mt|mb|ml|mr)-(0|1|2|3|4|5|6|8|10|12|16|20|24|32|40|48|56|64)$/
    },
    // Width and height
    {
      pattern: /^(w|h)-(0|1|2|3|4|5|6|7|8|9|10|11|12|14|16|20|24|28|32|36|40|44|48|52|56|60|64|72|80|96|auto|full|screen|min|max|fit)$/
    },
    // Custom semantic classes
    'hero', 'prose', 'cta-section', 'feature-grid', 'feature-card', 
    'btn-primary', 'btn-secondary', 'btn-accent'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
        },
        secondary: {
          50: '#faf5ff',
          100: '#f3e8ff',
          200: '#e9d5ff',
          300: '#d8b4fe',
          400: '#c084fc',
          500: '#a855f7',
          600: '#8b5cf6',
          700: '#7c3aed',
          800: '#6d28d9',
          900: '#5b21b6',
        },
        gray: {
          850: '#1a222e',
          950: '#0a0e16',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
}
```

---

## 🚀 Key Features to Implement

### 1. Live Preview with Auto-Refresh
```jsx
const [htmlCode, setHtmlCode] = useState('');
const [cssCode, setCssCode] = useState('');

// Combine HTML + CSS for preview
const previewContent = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ${cssCode}
    </style>
</head>
<body>
    ${htmlCode}
</body>
</html>
`;

return <iframe srcDoc={previewContent} className="w-full h-full border-0" />;
```

### 2. Tabbed Editor Interface
```jsx
const [activeEditor, setActiveEditor] = useState('html');

<div className="flex bg-gray-800 border-b border-gray-700">
    {['html', 'css', 'js'].map(tab => (
        <button
            key={tab}
            onClick={() => setActiveEditor(tab)}
            className={`px-4 py-3 font-medium ${
                activeEditor === tab 
                    ? 'bg-gray-900 text-blue-400 border-b-2 border-blue-500'
                    : 'text-gray-400 hover:text-gray-200'
            }`}
        >
            {tab.toUpperCase()}
        </button>
    ))}
</div>
```

### 3. Resizable Panels
```bash
npm install react-resizable-panels
```

```jsx
import { Panel, PanelGroup, PanelResizeHandle } from "react-resizable-panels";

<PanelGroup direction="horizontal">
    <Panel defaultSize={50} minSize={30}>
        {/* Preview */}
    </Panel>
    <PanelResizeHandle className="w-1 bg-gray-700 hover:bg-blue-500" />
    <Panel defaultSize={50} minSize={30}>
        {/* Editor */}
    </Panel>
</PanelGroup>
```

---

## 📝 CSS Class Whitelist for AI

```javascript
const CSS_CLASS_WHITELIST = [
    // Semantic Layout
    'hero', 'section', 'container', 'wrapper',
    
    // Components
    'feature-grid', 'feature-card', 'testimonial', 'pricing-card',
    'cta-section', 'stat-block', 'timeline', 'accordion',
    
    // Buttons
    'btn-primary', 'btn-secondary', 'btn-accent', 'btn-success', 'btn-ghost',
    
    // Typography
    'heading', 'subheading', 'lead', 'caption', 'prose',
    
    // Utility
    'card', 'badge', 'alert', 'divider', 'spacer'
];
```

### AI Prompt Template for Landing Pages
```javascript
const AI_PROMPT_TEMPLATE = `
You are an expert web designer creating a landing page.

RULES:
- Output clean, semantic HTML only
- Use ONLY these CSS classes: ${CSS_CLASS_WHITELIST.join(', ')}
- Mix Tailwind utilities (text-5xl, mb-6, etc.) with semantic classes
- Do NOT include <html>, <head>, or <body> tags
- No inline JavaScript or CSS
- Use semantic HTML5 tags (section, article, nav, etc.)
- Include Font Awesome icons where appropriate

STRUCTURE:
1. Hero section with heading, subheading, and CTA button
2. Feature grid (3-4 features)
3. Optional testimonial section
4. Final CTA section

Example structure:
<section class="hero">
    <h1 class="text-5xl font-bold mb-4">Your Headline</h1>
    <p class="text-xl mb-8">Compelling subheading</p>
    <a href="#" class="cta-button">Get Started</a>
</section>

<div class="feature-grid">
    <div class="feature-card">
        <i class="fas fa-rocket text-4xl text-blue-600 mb-4"></i>
        <h3 class="text-xl font-semibold mb-2">Feature Title</h3>
        <p class="text-gray-600">Description</p>
    </div>
</div>

Generate a landing page for: {USER_REQUEST}
`;
```

---

## 🎨 Example Landing Page Structure

```html
<!-- AI-generated semantic HTML -->
<div class="hero">
    <h1 class="text-5xl font-bold mb-4">Build Faster</h1>
    <p class="lead text-xl mb-8">Create beautiful landing pages in minutes</p>
    <a href="#" class="cta-button">Get Started Free</a>
</div>

<section class="section">
    <h2 class="text-3xl font-bold text-center mb-12">Features</h2>
    <div class="feature-grid">
        <div class="feature-card">
            <i class="fas fa-bolt text-blue-600 text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Lightning Fast</h3>
            <p class="text-gray-600">Optimized for performance</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt text-green-600 text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Secure</h3>
            <p class="text-gray-600">Enterprise-grade security</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-mobile-alt text-purple-600 text-4xl mb-4"></i>
            <h3 class="text-xl font-semibold mb-2">Responsive</h3>
            <p class="text-gray-600">Works on all devices</p>
        </div>
    </div>
</section>

<div class="cta-section">
    <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
    <p class="text-xl mb-6">Join thousands of satisfied customers</p>
    <a href="#" class="btn-primary">Start Your Free Trial</a>
</div>
```

---

## 🛠️ Monaco Editor Configuration

### Editor Options Reference
```javascript
editorOptions = {
    // Display
    minimap: { enabled: false },
    fontSize: 14,
    lineNumbers: 'on',
    glyphMargin: false,
    folding: true,
    lineDecorationsWidth: 10,
    lineNumbersMinChars: 3,
    
    // Behavior
    automaticLayout: true,
    scrollBeyondLastLine: false,
    wordWrap: 'on',
    wrappingStrategy: 'advanced',
    readOnly: false,
    tabSize: 2,
    insertSpaces: true,
    
    // Selection & Cursor
    roundedSelection: true,
    cursorStyle: 'line',
    cursorBlinking: 'blink',
    
    // Suggestions & Intellisense
    suggestOnTriggerCharacters: true,
    acceptSuggestionOnEnter: 'on',
    quickSuggestions: true,
    
    // Scrollbar
    scrollbar: {
        vertical: 'visible',
        horizontal: 'visible',
        verticalScrollbarSize: 10,
        horizontalScrollbarSize: 10,
    }
}
```

### Language-Specific Configuration
```javascript
// HTML
monaco.languages.html.htmlDefaults.setOptions({
    format: {
        tabSize: 2,
        insertSpaces: true,
        wrapLineLength: 120,
        wrapAttributes: 'auto',
    }
});

// CSS
monaco.languages.css.cssDefaults.setOptions({
    validate: true,
    lint: {
        compatibleVendorPrefixes: 'warning',
        vendorPrefix: 'warning',
        duplicateProperties: 'warning',
    }
});
```

---

## 🎯 Key Differences from Original CMS

### What to Keep
✅ Monaco editor for code editing  
✅ Dark theme UI (gray-900 base)  
✅ Semantic CSS classes over utility soup  
✅ CSS variable system  
✅ Tailwind + custom CSS hybrid  
✅ Split-screen layout (preview + editor)  

### What to Enhance
🚀 Real-time AI generation of landing pages  
🚀 Multiple layout templates  
🚀 Export functionality (HTML/CSS download)  
🚀 Undo/redo history  
🚀 Template library  
🚀 Drag-and-drop section builder (optional)  

### What to Remove
❌ Laravel/Blade syntax  
❌ Database persistence (unless needed)  
❌ Form builder (focus on static landing pages)  
❌ Multi-user/auth system (single-user tool)  

---

## 📦 Recommended Tech Stack

```json
{
  "name": "ai-web-design-workbench",
  "dependencies": {
    "react": "^18.2.0",
    "@monaco-editor/react": "^4.7.0",
    "monaco-editor": "^0.55.1",
    "react-resizable-panels": "^2.0.0",
    "openai": "^4.0.0",
    "tailwindcss": "^3.4.0",
    "@tailwindcss/typography": "^0.5.10"
  }
}
```

### Suggested File Structure
```
ai-web-design-workbench/
├── src/
│   ├── components/
│   │   ├── MonacoEditor.jsx
│   │   ├── PreviewPanel.jsx
│   │   ├── EditorTabs.jsx
│   │   └── TemplateLibrary.jsx
│   ├── hooks/
│   │   ├── useAIGeneration.js
│   │   └── useLocalStorage.js
│   ├── utils/
│   │   ├── cssClassWhitelist.js
│   │   └── defaultTemplates.js
│   └── App.jsx
├── tailwind.config.js
└── package.json
```

---

## 🔥 Quick Start Checklist

- [ ] Install Monaco Editor packages
- [ ] Set up Tailwind CSS with safelist
- [ ] Create MonacoEditor component
- [ ] Implement split-screen layout
- [ ] Add tabbed editor interface
- [ ] Set up live preview with iframe
- [ ] Define CSS class whitelist
- [ ] Create semantic CSS classes
- [ ] Implement AI prompt templates
- [ ] Add export functionality
- [ ] Create template library

---

## 💡 Tips & Best Practices

1. **Editor Performance**: Use `automaticLayout: true` to handle resize events
2. **Preview Security**: Use `srcDoc` instead of `src` for iframe to avoid CORS
3. **CSS Variables**: Always provide fallbacks for older browsers
4. **AI Prompts**: Be specific about whitelist classes to avoid hallucination
5. **State Management**: Consider Zustand or Context API for editor state
6. **Autosave**: Implement localStorage backup every few seconds
7. **Error Handling**: Catch and display HTML/CSS syntax errors in preview

---

This guide captures the essence of your CMS's design philosophy: **opinionated structure with flexibility, semantic classes over utility soup, and a professional VSCode-style editing experience**. Your new workbench should feel like a more powerful, AI-enhanced version of the CMS editor!
