
let cartCount = parseInt(document.getElementById('cartCount')?.textContent || '0');

document.querySelectorAll('.add-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const { id, name, price, emoji } = btn.dataset;
    addToCart(parseInt(id), name, parseFloat(price), emoji, btn);
  });
});

function addToCart(id, name, price, emoji, btn) {
  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<span style="color:#22c55e;font-size:1.1rem">✓</span><span>Added!</span>';
  btn.style.background = 'rgba(34,197,94,.15)';
  btn.style.borderColor = 'rgba(34,197,94,.4)';
  btn.style.color = '#86efac';
  btn.disabled = true;

  fetch('api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ action: 'add', id, name, price })
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      cartCount = data.cartCount;
      updateCartUI();
      showToast(emoji, `${name} added to cart`);
    }
  })
  .finally(() => {
    setTimeout(() => {
      btn.innerHTML = originalHTML;
      btn.style.background = '';
      btn.style.borderColor = '';
      btn.style.color = '';
      btn.disabled = false;
    }, 1200);
  });
}

function updateCartUI() {
  const badge     = document.getElementById('cartCount');
  const floatBar  = document.getElementById('floatBar');
  const floatCount = document.getElementById('floatCount');

  if (badge) {
    badge.textContent = cartCount;
    badge.style.transform = 'scale(1.4)';
    setTimeout(() => badge.style.transform = '', 300);
  }
  if (floatCount) {
    floatCount.textContent = cartCount;
    document.querySelector('#floatBar span')?.lastChild && (document.querySelector('.float-count').innerHTML =
      `<span id="floatCount">${cartCount}</span> item${cartCount !== 1 ? 's' : ''}`);
  }
  if (floatBar) {
    if (cartCount > 0) floatBar.classList.add('visible');
    else floatBar.classList.remove('visible');
  }
}

// Show float bar if cart already has items
updateCartUI();

function showToast(emoji, msg) {
  const toast = document.getElementById('toast');
  document.getElementById('toastEmoji').textContent = emoji;
  document.getElementById('toastMsg').textContent   = msg;
  toast.classList.add('show');
  clearTimeout(toast._timeout);
  toast._timeout = setTimeout(() => toast.classList.remove('show'), 2800);
}

const sections  = document.querySelectorAll('.menu-section');
const tabBtns   = document.querySelectorAll('.tab-btn');

tabBtns.forEach(btn => {
  btn.addEventListener('click', () => {
    const target = document.getElementById(btn.dataset.target);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});

// Intersection Observer for active tab
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const id = entry.target.id;
      tabBtns.forEach(btn => {
        btn.classList.toggle('active', btn.dataset.target === id);
      });
    }
  });
}, { rootMargin: '-30% 0px -60% 0px', threshold: 0 });

sections.forEach(s => observer.observe(s));

document.querySelectorAll('.menu-card').forEach((card, i) => {
  card.style.animationDelay = `${(i % 4) * 80}ms`;
});