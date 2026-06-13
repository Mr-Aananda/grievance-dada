<template>
    <Teleport to="body">
        <Transition name="gms-lightbox-fade">
            <div v-if="open" class="gms-lightbox" @click.self="closeLightbox">
                <div class="gms-lightbox-container">
                    <!-- Header -->
                    <div class="gms-lightbox-header">
                        <div class="gms-lightbox-title">
                            <i class="bi bi-image"></i>
                            <span>{{ currentItem?.original_name || currentItem?.file_name || 'Image Viewer' }}</span>
                        </div>
                        <div class="gms-lightbox-actions">
                            <a :href="currentItem?.url" :download="currentItem?.file_name"
                               class="gms-lightbox-btn" title="Download" @click.stop>
                                <i class="bi bi-download"></i>
                            </a>
                            <a :href="currentItem?.url" target="_blank" rel="noopener"
                               class="gms-lightbox-btn" title="Open in new tab" @click.stop>
                                <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                            <button class="gms-lightbox-btn" @click="closeLightbox" title="Close" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Navigation Arrows -->
                    <button v-if="hasPrevious" class="gms-lightbox-nav gms-lightbox-prev" @click.stop="previousImage" aria-label="Previous image">
                        <i class="bi bi-chevron-left"></i>
                    </button>

                    <button v-if="hasNext" class="gms-lightbox-nav gms-lightbox-next" @click.stop="nextImage" aria-label="Next image">
                        <i class="bi bi-chevron-right"></i>
                    </button>

                    <!-- Image Container -->
                    <div class="gms-lightbox-content" @click.self="closeLightbox">
                        <img v-if="currentItem" :src="currentItem.url" :alt="currentItem.original_name || currentItem.file_name"
                             class="gms-lightbox-image" />
                    </div>

                    <!-- Counter -->
                    <div v-if="images.length > 1" class="gms-lightbox-counter">
                        {{ currentIndex + 1 }} / {{ images.length }}
                    </div>

                    <!-- Thumbnails -->
                    <div v-if="images.length > 1" class="gms-lightbox-thumbnails">
                        <div v-for="(img, idx) in images" :key="idx"
                             :class="['gms-thumbnail', { active: idx === currentIndex }]"
                             @click.stop="goToImage(idx)">
                            <img :src="img.url" :alt="img.original_name || img.file_name" loading="lazy" />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    open: Boolean,
    index: { type: Number, default: 0 },
    images: { type: Array, default: () => [] },
});

const emit = defineEmits(['close', 'prev', 'next', 'go-to']);

const currentIndex = computed(() => props.index);
const currentItem = computed(() => props.images?.[props.index] ?? null);
const hasPrevious = computed(() => props.images && props.index > 0);
const hasNext = computed(() => props.images && props.index < props.images.length - 1);

function closeLightbox() {
    emit('close');
}

function previousImage() {
    if (hasPrevious.value) emit('prev');
}

function nextImage() {
    if (hasNext.value) emit('next');
}

function goToImage(index) {
    if (index !== currentIndex.value) {
        emit('go-to', index);
    }
}

// Keyboard navigation
function handleKeydown(e) {
    if (!props.open) return;

    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowLeft') {
        previousImage();
    } else if (e.key === 'ArrowRight') {
        nextImage();
    }
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
});

watch(() => props.open, (newVal) => {
    document.body.style.overflow = newVal ? 'hidden' : '';
});
</script>

<style>
/* Self-contained styles (component is teleported to <body>,
   so it can't rely on .gms-scope-scoped CSS variables). */

.gms-lightbox {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.92);
    z-index: 1100;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gms-lightbox-container {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.gms-lightbox-header {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    padding: 16px 24px;
    background: linear-gradient(to bottom, rgba(0,0,0,0.7), transparent);
    color: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 2;
    gap: 16px;
}

.gms-lightbox-title {
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    gap: 8px;
    min-width: 0;
}

.gms-lightbox-actions {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.gms-lightbox-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    border: none;
    color: #ffffff;
    cursor: pointer;
    transition: background 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 16px;
}

.gms-lightbox-btn:hover {
    background: rgba(255, 255, 255, 0.25);
}

.gms-lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    border: none;
    color: #ffffff;
    cursor: pointer;
    transition: background 0.2s ease;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.gms-lightbox-nav:hover {
    background: rgba(255, 255, 255, 0.25);
}

.gms-lightbox-nav.gms-lightbox-prev {
    left: 24px;
}

.gms-lightbox-nav.gms-lightbox-next {
    right: 24px;
}

.gms-lightbox-content {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px;
    min-height: 0;
}

.gms-lightbox-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
}

.gms-lightbox-counter {
    position: absolute;
    bottom: 100px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0, 0, 0, 0.6);
    padding: 6px 16px;
    border-radius: 50px;
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
    z-index: 2;
}

.gms-lightbox-thumbnails {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 16px 24px;
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    display: flex;
    gap: 8px;
    justify-content: center;
    overflow-x: auto;
    z-index: 2;
}

.gms-thumbnail {
    width: 56px;
    height: 56px;
    border-radius: 6px;
    overflow: hidden;
    cursor: pointer;
    flex-shrink: 0;
    border: 2px solid transparent;
    opacity: 0.6;
    transition: opacity 0.2s ease, border-color 0.2s ease;
}

.gms-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.gms-thumbnail:hover {
    opacity: 0.9;
}

.gms-thumbnail.active {
    border-color: #ffffff;
    opacity: 1;
}

.gms-lightbox-fade-enter-active,
.gms-lightbox-fade-leave-active {
    transition: opacity 0.25s ease;
}

.gms-lightbox-fade-enter-from,
.gms-lightbox-fade-leave-to {
    opacity: 0;
}

@media (max-width: 768px) {
    .gms-lightbox-nav {
        width: 40px;
        height: 40px;
    }

    .gms-lightbox-nav.gms-lightbox-prev {
        left: 12px;
    }

    .gms-lightbox-nav.gms-lightbox-next {
        right: 12px;
    }

    .gms-lightbox-content {
        padding: 60px 16px 120px;
    }

    .gms-lightbox-header {
        padding: 12px 16px;
    }
}
</style>
