

<?php $__env->startSection('title', $property->title); ?>

<?php $__env->startSection('content'); ?>
<?php
  $images = $property->images ?? [];
  $cover  = $images[0] ?? null;

  // Niz punih URL-ova (storage)
  $imageUrls = collect($images)
      ->map(fn($p) => asset('storage/'.$p))
      ->values()
      ->all();
?>

<div class="container mx-auto mt-6">
  
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <div class="flex items-center gap-2">
        <?php if($property->category?->type): ?>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 border">
            <?php echo e(__($property->category->type === 'rent' ? 'Rent' : 'Sale')); ?>

          </span>
        <?php endif; ?>
        <?php if($property->city): ?>
          <span class="text-xs px-2 py-1 rounded-full bg-gray-100 border">
            <?php echo e($property->city); ?>

          </span>
        <?php endif; ?>
      </div>
      <h1 class="text-2xl md:text-3xl font-bold mt-2">
        <?php echo e($property->title_localized); ?>

      </h1>
      <div class="text-gray-600"><?php echo e($property->address); ?></div>
    </div>

    <div class="text-right">
      <?php if(!is_null($property->price)): ?>
        <div class="text-3xl font-semibold">€<?php echo e(number_format($property->price,0,',','.')); ?></div>
      <?php endif; ?>
      <div class="text-gray-600">
        <?php echo e($property->area); ?> m²
        <?php if(!is_null($property->rooms)): ?> · <?php echo e($property->rooms); ?> <?php echo e(__('Rooms')); ?> <?php endif; ?>
        <?php if(!is_null($property->floor)): ?> · <?php echo e($property->floor); ?> <?php echo e(__('Floor')); ?> <?php endif; ?>
      </div>
    </div>
  </div>

 
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
  
  <div class="md:col-span-2 rounded-2xl overflow-hidden border">
    <img
      src="<?php echo e($cover ? asset('storage/'.$cover) : 'https://placehold.co/1200x800'); ?>"
      alt="<?php echo e($property->title); ?>"
      class="w-full h-[360px] md:h-[520px] object-cover cursor-pointer"
      data-gallery-open="0"
    >
  </div>

  
  <div class="grid grid-cols-3 md:grid-cols-1 gap-4">
    <?php $__empty_1 = true; $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <?php if($i === 0) continue; ?>
      <img
        src="<?php echo e(asset('storage/'.$img)); ?>"
        alt="Image <?php echo e($i+1); ?>"
        class="w-full h-28 md:h-40 object-cover rounded-xl border cursor-pointer"
        data-gallery-open="<?php echo e($i); ?>"
      >
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="col-span-3 text-gray-500 border rounded-xl p-4 text-center">
        <?php echo e(__('No additional images')); ?>

      </div>
    <?php endif; ?>
  </div>
</div>

  
  <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
      <section class="rounded-2xl border p-5 bg-white">
        <h2 class="text-lg font-semibold mb-3"><?php echo e(__('Description')); ?></h2>
        <div class="prose max-w-none">
          <?php echo nl2br(e($property->description_localized)); ?>

        </div>
      </section>

      
      
      

      <?php if($property->lat && $property->lng): ?>
        <section class="rounded-2xl border overflow-hidden">
          <iframe
            src="https://maps.google.com/maps?q=<?php echo e($property->lat); ?>,<?php echo e($property->lng); ?>&z=15&output=embed"
            class="w-full h-80"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            aria-label="<?php echo e(__('Map')); ?>"></iframe>
        </section>
      <?php endif; ?>
    </div>

    <aside class="space-y-6">
      <div class="rounded-2xl border p-5 bg-white">
        <h3 class="text-lg font-semibold mb-3"><?php echo e(__('Key details')); ?></h3>
        <dl class="grid grid-cols-2 gap-3 text-sm">
          <dt class="text-gray-600"><?php echo e(__('Type')); ?></dt>
          <dd class="font-medium"><?php echo e(__($property->category?->type === 'rent' ? 'Rent' : 'Sale')); ?></dd>

          <dt class="text-gray-600"><?php echo e(__('City')); ?></dt>
          <dd class="font-medium"><?php echo e($property->city); ?></dd>

          <dt class="text-gray-600"><?php echo e(__('Area')); ?></dt>
          <dd class="font-medium"><?php echo e($property->area); ?> m²</dd>

          <dt class="text-gray-600"><?php echo e(__('Rooms')); ?></dt>
          <dd class="font-medium"><?php echo e($property->rooms ?? '—'); ?></dd>

          <dt class="text-gray-600"><?php echo e(__('Floor')); ?></dt>
          <dd class="font-medium"><?php echo e($property->floor ?? '—'); ?></dd>

          <dt class="text-gray-600"><?php echo e(__('Address')); ?></dt>
          <dd class="font-medium"><?php echo e($property->address ?? '—'); ?></dd>

          <dt class="text-gray-600"><?php echo e(__('Price')); ?></dt>
          <dd class="font-medium">€<?php echo e(number_format($property->price,0,',','.')); ?></dd>
        </dl>
      </div>

      <div class="rounded-2xl border p-5 bg-white">
        <h3 class="text-lg font-semibold mb-3"><?php echo e(__('Share')); ?></h3>
        <div class="flex gap-2">
          <a class="px-3 py-1.5 rounded-lg border hover:bg-gray-50"
             href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->fullUrl())); ?>" target="_blank" rel="noopener">Facebook</a>
          <a class="px-3 py-1.5 rounded-lg border hover:bg-gray-50"
             href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->fullUrl())); ?>" target="_blank" rel="noopener">X</a>
        </div>
      </div>
    </aside>
  </div>
</div>

<script type="application/json" id="property-images-json">
  <?php echo json_encode($imageUrls, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

</script>


<div id="galleryModal" class="fixed inset-0 z-50 hidden items-center justify-center">
  <div class="absolute inset-0 bg-black/70" data-gallery-close></div>

  <div class="relative w-[min(95vw,1200px)] h-[82vh] md:h-[86vh] px-4">
    <img id="galleryImage" src="" alt="gallery"
         class="w-full h-full object-contain rounded-lg shadow-lg select-none" />

    
    <button id="galleryPrev"
            class="absolute left-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">
      ‹
    </button>
    <button id="galleryNext"
            class="absolute right-2 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/80 hover:bg-white shadow">
      ›
    </button>
    <button id="galleryClose"
            class="absolute -right-1 -top-1 p-2 rounded-full bg-white/90 hover:bg-white shadow">
      ✕
    </button>
  </div>
</div>

<script>
(function () {
  const dataEl = document.getElementById('property-images-json');
  const IMAGES = dataEl ? JSON.parse(dataEl.textContent) : [];
  if (!IMAGES.length) return;

  const modal = document.getElementById('galleryModal');
  const imgEl = document.getElementById('galleryImage');
  const btnPrev = document.getElementById('galleryPrev');
  const btnNext = document.getElementById('galleryNext');
  const btnClose = document.getElementById('galleryClose');
  const backdropClose = modal.querySelector('[data-gallery-close]');

  let index = 0;
  let touchStartX = null;

  function show(i) {
    index = (i + IMAGES.length) % IMAGES.length;
    imgEl.src = IMAGES[index];
  }

  function open(i) {
    show(i);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  }

  function close() {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
  }

  function next() { show(index + 1); }
  function prev() { show(index - 1); }

  // Otvaranje sa svih elemenata koji imaju data-gallery-open
  document.querySelectorAll('[data-gallery-open]').forEach(el => {
    el.addEventListener('click', () => {
      const i = parseInt(el.getAttribute('data-gallery-open'), 10) || 0;
      open(i);
    });
  });

  // Kontrole
  btnNext.addEventListener('click', next);
  btnPrev.addEventListener('click', prev);
  btnClose.addEventListener('click', close);
  backdropClose.addEventListener('click', close);

  // Tastatura
  document.addEventListener('keydown', (e) => {
    if (modal.classList.contains('hidden')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight') next();
    if (e.key === 'ArrowLeft') prev();
  });

  // Swipe (mobile)
  imgEl.addEventListener('touchstart', (e) => {
    touchStartX = e.touches[0].clientX;
  }, {passive: true});
  imgEl.addEventListener('touchend', (e) => {
    if (touchStartX === null) return;
    const dx = e.changedTouches[0].clientX - touchStartX;
    if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); }
    touchStartX = null;
  });
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Desktop\111\realestate\resources\views/properties/show.blade.php ENDPATH**/ ?>