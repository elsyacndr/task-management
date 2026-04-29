# Login/Register UI Redesign Plan

**Information Gathered:**
- index.php (login): Basic centered glass-card form with email/password
- register.php: Similar form with name/email/password/confirm
- Both use .auth-container/.auth-card from style.css
- Dashboard uses full sidebar + main content with glassmorphism
- Need desktop-focused (wide layouts) but mobile-responsive

**Plan:**
1. **Visual Style:** Match dashboard glassmorphism - larger hero background, animated cards, gradient overlays
2. **Layout:** Split-screen desktop (image/form), stacked mobile
3. **Animations:** Fade-in, floating labels, button hover effects
4. **Responsive:** Mobile-first, desktop-optimized

**Files:**
- index.php, register.php (HTML structure)
- assets/css/style.css (extend auth styles)
- JS for animations (inline or app.js)

**Approve to proceed?**

