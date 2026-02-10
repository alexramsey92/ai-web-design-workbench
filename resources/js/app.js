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

// Feature Carousel Animation
document.addEventListener('DOMContentLoaded', function() {
  const carousel = document.querySelector('.feature-carousel');
  if (!carousel) return;
  
  const badges = carousel.querySelectorAll('.feature-badge');
  if (badges.length === 0) return;
  
  let currentIndex = 0;
  const rotationInterval = 3000; // 3 seconds
  
  function rotateBadges() {
    // Remove active class from current badge
    badges[currentIndex].classList.remove('active');
    
    // Move to next badge (loop back to start)
    currentIndex = (currentIndex + 1) % badges.length;
    
    // Add active class to new badge
    badges[currentIndex].classList.add('active');
    badges[currentIndex].classList.add('entering');
    
    // Remove entering class after animation
    setTimeout(() => {
      badges[currentIndex].classList.remove('entering');
    }, 700);
  }
  
  // Start automatic rotation
  const rotationTimer = setInterval(rotateBadges, rotationInterval);
  
  // Allow manual selection
  badges.forEach((badge, index) => {
    badge.addEventListener('click', function() {
      if (index === currentIndex) return;
      
      // Clear and restart timer
      clearInterval(rotationTimer);
      
      badges[currentIndex].classList.remove('active');
      currentIndex = index;
      badges[currentIndex].classList.add('active');
      
      // Restart automatic rotation
      setTimeout(() => {
        setInterval(rotateBadges, rotationInterval);
      }, 100);
    });
  });
});
