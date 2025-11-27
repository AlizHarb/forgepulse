/**
 * ForgePulse Documentation JavaScript
 */

// Initialize all features when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  initNavbarScroll();
  if (typeof hljs !== "undefined") {
    hljs.highlightAll();
  }

  initCopyButtons();
  initSmoothScroll();
  initActiveNavLinks();
  initTableOfContents();
  initMobileMenu();
  initBackToTop();
});

// Navbar scroll animation
function initNavbarScroll() {
  const header = document.querySelector(".header");
  let lastScroll = 0;

  window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }

    lastScroll = currentScroll;
  });
}

// Copy button functionality
function initCopyButtons() {
  document.querySelectorAll("pre").forEach((pre) => {
    if (!pre.querySelector(".copy-btn")) {
      const button = document.createElement("button");
      button.className = "copy-btn";
      button.innerHTML = `
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                <span>Copy</span>
            `;

      button.addEventListener("click", async () => {
        const code = pre.querySelector("code");
        const text = code.textContent;

        try {
          await navigator.clipboard.writeText(text);
          button.classList.add("copied");
          button.innerHTML = `
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Copied!</span>
                    `;

          setTimeout(() => {
            button.classList.remove("copied");
            button.innerHTML = `
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span>Copy</span>
                        `;
          }, 2000);
        } catch (err) {
          console.error("Failed to copy:", err);
        }
      });

      pre.appendChild(button);
    }
  });
}

// Smooth scrolling for anchor links
function initSmoothScroll() {
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      if (href === "#") return;

      e.preventDefault();
      const target = document.querySelector(href);

      if (target) {
        const offset = 100;
        const targetPosition =
          target.getBoundingClientRect().top + window.pageYOffset - offset;

        window.scrollTo({
          top: targetPosition,
          behavior: "smooth",
        });

        // Update URL without jumping
        history.pushState(null, null, href);
      }
    });
  });
}

// Update active navigation links based on scroll position
function initActiveNavLinks() {
  const sections = document.querySelectorAll("section[id], h2[id], h3[id]");
  const navLinks = document.querySelectorAll(".nav-link, .toc-link");

  if (sections.length === 0 || navLinks.length === 0) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const id = entry.target.getAttribute("id");

          navLinks.forEach((link) => {
            const href = link.getAttribute("href");
            if (href === `#${id}`) {
              link.classList.add("active");
            } else {
              link.classList.remove("active");
            }
          });
        }
      });
    },
    {
      rootMargin: "-100px 0px -66%",
      threshold: 0,
    }
  );

  sections.forEach((section) => observer.observe(section));
}

// Generate table of contents
function initTableOfContents() {
  const toc = document.querySelector(".toc-links");
  if (!toc) return;

  const headings = document.querySelectorAll(".content h2, .content h3");

  headings.forEach((heading) => {
    if (!heading.id) {
      heading.id = heading.textContent
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, "-");
    }

    const link = document.createElement("a");
    link.href = `#${heading.id}`;
    link.className = "toc-link";
    link.textContent = heading.textContent;

    if (heading.tagName === "H3") {
      link.style.paddingLeft = "1.5rem";
      link.style.fontSize = "0.8125rem";
    }

    toc.appendChild(link);
  });
}

// Mobile menu functionality
function initMobileMenu() {
  // Create mobile menu toggle button if it doesn't exist
  const headerActions = document.querySelector(".header-actions");
  if (!headerActions) return;

  // Check if button already exists
  if (document.querySelector(".mobile-menu-toggle")) return;

  const toggleBtn = document.createElement("button");
  toggleBtn.className = "mobile-menu-toggle";
  toggleBtn.innerHTML = `
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <span>Menu</span>
    `;

  // Insert as first child
  headerActions.insertBefore(toggleBtn, headerActions.firstChild);

  // Create overlay
  let overlay = document.querySelector(".sidebar-overlay");
  if (!overlay) {
    overlay = document.createElement("div");
    overlay.className = "sidebar-overlay";
    document.body.appendChild(overlay);
  }

  // Add close button to sidebar
  const sidebar = document.querySelector(".sidebar");
  if (sidebar && !sidebar.querySelector(".sidebar-close")) {
    const closeDiv = document.createElement("div");
    closeDiv.className = "sidebar-close";
    closeDiv.innerHTML = `
            <strong>Navigation</strong>
            <button class="close-sidebar">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
    sidebar.insertBefore(closeDiv, sidebar.firstChild);
  }

  // Toggle menu
  toggleBtn.addEventListener("click", () => {
    sidebar.classList.add("mobile-open");
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
  });

  // Close menu
  const closeMenu = () => {
    sidebar.classList.remove("mobile-open");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  };

  overlay.addEventListener("click", closeMenu);

  const closeBtn = sidebar.querySelector(".close-sidebar");
  if (closeBtn) {
    closeBtn.addEventListener("click", closeMenu);
  }

  // Close menu when clicking nav links on mobile
  const navLinks = sidebar.querySelectorAll(".nav-link");
  navLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 768) {
        closeMenu();
      }
    });
  });
}

// Search functionality (placeholder for future implementation)
function initSearch() {
  const searchInput = document.querySelector(".search-input");
  if (!searchInput) return;

  searchInput.addEventListener("input", (e) => {
    const query = e.target.value.toLowerCase();
    // Implement search logic here
    console.log("Searching for:", query);
  });
}

// Back to Top functionality
function initBackToTop() {
    const button = document.createElement('button');
    button.className = 'back-to-top';
    button.innerHTML = `
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    `;
    button.title = "Back to Top";
    
    document.body.appendChild(button);
    
    const toggleButton = () => {
        if (window.scrollY > 300) {
            button.classList.add('visible');
        } else {
            button.classList.remove('visible');
        }
    };
    
    window.addEventListener('scroll', toggleButton);
    
    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}
