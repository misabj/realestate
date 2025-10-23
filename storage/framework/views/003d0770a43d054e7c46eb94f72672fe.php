

<?php $__env->startSection('title', __('Properties')); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto mt-6 grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6">

  
  <aside class="lg:sticky lg:top-6 h-fit rounded-2xl border bg-white/70 backdrop-blur">
    <form method="GET" action="<?php echo e(route('properties.index')); ?>" class="p-5 space-y-4">
      <h2 class="text-lg font-semibold"><?php echo e(__('Filters')); ?></h2>

      
      <div class="grid grid-cols-2 rounded-xl overflow-hidden border">
        <label class="cursor-pointer">
          <input type="radio" class="hidden" name="type" value="rent" <?php if(request('type')==='rent'): echo 'checked'; endif; ?>>
          <div class="px-3 py-2 text-center <?php echo e(request('type')==='rent' ? 'bg-black text-white' : 'hover:bg-gray-50'); ?>">
            <?php echo e(__('Rent')); ?>

          </div>
        </label>
        <label class="cursor-pointer">
          <input type="radio" class="hidden" name="type" value="sale" <?php if(request('type')==='sale'): echo 'checked'; endif; ?>>
          <div class="px-3 py-2 text-center <?php echo e(request('type')==='sale' ? 'bg-black text-white' : 'hover:bg-gray-50'); ?>">
            <?php echo e(__('Sale')); ?>

          </div>
        </label>
      </div>

      
      <div>
        <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Location (city or address)')); ?></label>
        <input name="q" value="<?php echo e(request('q')); ?>" placeholder="<?php echo e(__('Belgrade, Dorćol...')); ?>"
               class="w-full px-3 py-2 rounded-lg border">
      </div>

      
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Min price')); ?></label>
          <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0" step="100">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Max price')); ?></label>
          <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0" step="100">
        </div>
      </div>

      
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Min rooms')); ?></label>
          <input type="number" name="rooms_min" value="<?php echo e(request('rooms_min')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Max rooms')); ?></label>
          <input type="number" name="rooms_max" value="<?php echo e(request('rooms_max')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0">
        </div>
      </div>

      
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Min area (m²)')); ?></label>
          <input type="number" name="area_min" value="<?php echo e(request('area_min')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0" step="5">
        </div>
        <div>
          <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Max area (m²)')); ?></label>
          <input type="number" name="area_max" value="<?php echo e(request('area_max')); ?>" class="w-full px-3 py-2 rounded-lg border" min="0" step="5">
        </div>
      </div>

      
      <div>
        <label class="block text-sm text-gray-600 mb-1"><?php echo e(__('Sort by')); ?></label>
        <select name="sort" class="w-full px-3 py-2 rounded-lg border">
          <option value="newest" <?php if(request('sort')==='newest' || !request()->has('sort')): echo 'selected'; endif; ?>><?php echo e(__('Newest')); ?></option>
          <option value="price_asc" <?php if(request('sort')==='price_asc'): echo 'selected'; endif; ?>><?php echo e(__('Price ↑')); ?></option>
          <option value="price_desc" <?php if(request('sort')==='price_desc'): echo 'selected'; endif; ?>><?php echo e(__('Price ↓')); ?></option>
          <option value="area_desc" <?php if(request('sort')==='area_desc'): echo 'selected'; endif; ?>><?php echo e(__('Area ↓')); ?></option>
        </select>
      </div>

      
      <div class="flex gap-2 pt-2">
        <button class="flex-1 bg-black text-white rounded-lg py-2 hover:bg-black/90"><?php echo e(__('Apply')); ?></button>
        <a href="<?php echo e(route('properties.index')); ?>" class="px-4 py-2 rounded-lg border"><?php echo e(__('Clear')); ?></a>
      </div>
    </form>
  </aside>

  
  <section>
    
    <?php
      $map = [
        'type' => ['label' => __('Type')],
        'q' => ['label' => __('Location')],
        'min_price' => ['label' => __('Min price')],
        'max_price' => ['label' => __('Max price')],
        'rooms_min' => ['label' => __('Min rooms')],
        'rooms_max' => ['label' => __('Max rooms')],
        'area_min' => ['label' => __('Min area (m²)')],
        'area_max' => ['label' => __('Max area (m²)')],
        'sort' => ['label' => __('Sort')],
      ];
      $active = collect(request()->only(array_keys($map)))->filter(fn($v) => filled($v));
    ?>

    <?php if($active->isNotEmpty()): ?>
      <div class="mb-4 flex flex-wrap items-center gap-2">
        <?php $__currentLoopData = $active; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(request()->fullUrlWithQuery([$key => null])); ?>"
             class="inline-flex items-center gap-2 text-sm px-3 py-1.5 rounded-full border bg-white hover:bg-gray-50">
            <span class="text-gray-600"><?php echo e($map[$key]['label']); ?>:</span>
            <span class="font-medium"><?php echo e(e($val)); ?></span>
            <span class="text-gray-400">✕</span>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('properties.index')); ?>" class="text-sm underline ml-2"><?php echo e(__('Clear all')); ?></a>
      </div>
    <?php endif; ?>

    
    <?php if($properties->count()): ?>
      <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('properties.show',$p->slug)); ?>" class="group relative border rounded-2xl overflow-hidden bg-white card">
            <?php $img = $p->images[0] ?? null; ?>
            <div class="relative">
              <img src="<?php echo e($img ? asset('storage/'.$img) : 'https://placehold.co/1200x800'); ?>"
                   alt="<?php echo e($p->title); ?>" class="h-56 w-full object-cover group-hover:scale-[1.02] transition duration-500">
              <?php if($p->category?->type): ?>
                <span class="absolute top-3 left-3 text-xs px-2 py-1 rounded-full backdrop-blur bg-white/85 border">
                  <?php echo e(__($p->category->type === 'rent' ? 'Rent' : 'Sale')); ?>

                </span>
              <?php endif; ?>
            </div>
            <div class="p-4">
              <div class="font-semibold line-clamp-1"><?php echo e($p->title_localized ?? $p->title); ?></div>
              <div class="text-sm text-gray-600 line-clamp-1">
                <?php echo e($p->city_localized ?? $p->city); ?> · <?php echo e($p->area); ?> m² · €<?php echo e(number_format($p->price,0,',','.')); ?>

              </div>
              <div class="mt-3 flex gap-2 text-xs">
                <?php if(!is_null($p->rooms)): ?>
                  <span class="px-2 py-1 rounded-full bg-gray-100"><?php echo e($p->rooms); ?> <?php echo e(__('Rooms')); ?></span>
                <?php endif; ?>
                <?php if(!is_null($p->floor)): ?>
                  <span class="px-2 py-1 rounded-full bg-gray-100"><?php echo e($p->floor); ?> <?php echo e(__('Floor')); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div class="mt-8">
        <?php echo e($properties->onEachSide(1)->links()); ?>

      </div>
    <?php else: ?>
      <div class="rounded-2xl border p-8 bg-white text-center">
        <div class="text-3xl">🔎</div>
        <h3 class="mt-2 text-lg font-semibold"><?php echo e(__('No results match your filters')); ?></h3>
        <p class="text-gray-600"><?php echo e(__('Try widening your search or clearing filters.')); ?></p>
        <a href="<?php echo e(route('properties.index')); ?>" class="inline-block mt-4 px-4 py-2 rounded-lg border hover:bg-gray-50">
          <?php echo e(__('Clear all filters')); ?>

        </a>
      </div>
    <?php endif; ?>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Administrator\Desktop\111\realestate\resources\views/properties/index.blade.php ENDPATH**/ ?>