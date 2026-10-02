/* assets/js/main.js — public site interactions (no data logic; content comes from PHP/MySQL) */
document.addEventListener('DOMContentLoaded', function () {

  /* Reusable crossfade slideshow — used by the homepage hero and the dining page hero */
  function initSlideshow(selector, intervalMs) {
    const slides = document.querySelectorAll(selector);
    if (slides.length > 1) {
      let index = 0;
      setInterval(() => {
        slides[index].classList.remove('active');
        index = (index + 1) % slides.length;
        slides[index].classList.add('active');
      }, intervalMs);
    }
  }
  initSlideshow('.hero-bg', 3000);
  initSlideshow('.menu-hero-bg', 3500);

  /* Featured video — force playback in case the browser delayed autoplay */
  const featuredVideo = document.querySelector('.video-frame video');
  if (featuredVideo) {
    const tryPlay = () => featuredVideo.play().catch(() => {});
    tryPlay();
    document.addEventListener('click', tryPlay, { once: true });
  }

  /* Nav scroll behaviour */
  const nav = document.getElementById('nav');
  const heroH = window.innerHeight * 0.72;
  function onScroll(){
    if (window.scrollY > 40) nav.classList.add('scrolled'); else nav.classList.remove('scrolled');
    if (window.scrollY > heroH) nav.classList.remove('on-hero'); else nav.classList.add('on-hero');
    const toTop = document.getElementById('toTop');
    if (toTop) toTop.classList.toggle('show', window.scrollY > 700);
  }
  if (nav) { window.addEventListener('scroll', onScroll); onScroll(); }

  /* Mobile nav toggle */
  const navToggle = document.getElementById('navToggle');
  const navLinks = document.getElementById('navLinks');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
    navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', () => navLinks.classList.remove('open')));
  }

  /* Scroll to top */
  const toTop = document.getElementById('toTop');
  if (toTop) toTop.addEventListener('click', () => window.scrollTo({top:0, behavior:'smooth'}));

  /* Reveal on scroll */
  const revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, {threshold:0.12});
    revealEls.forEach(el => io.observe(el));
  }

  /* Lightbox */
  const lightbox = document.getElementById('lightbox');
  const lbImg = document.getElementById('lbImg');
  const lbClose = document.getElementById('lbClose');
  document.querySelectorAll('[data-lightbox]').forEach(item => {
    item.addEventListener('click', () => {
      lbImg.src = item.getAttribute('data-lightbox');
      lightbox.classList.add('show');
    });
  });
  if (lbClose) lbClose.addEventListener('click', () => lightbox.classList.remove('show'));
  if (lightbox) lightbox.addEventListener('click', (e) => { if (e.target === lightbox) lightbox.classList.remove('show'); });
});
